<?php

interface Middleware
{
    /**
     * @return void
     */
    public function handle();
}

class JsonMiddleware implements Middleware
{
    public function handle(): bool
    {
        header('Content-Type: application/json; charset=UTF-8');

        return true;
    }
}

class ValidationMiddleware implements Middleware
{
    private array $rules;

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parseURI();
        $uri = '/' . implode('/', $uri);

        if (isset($this->rules["$method $uri"])) {
            $data = $_REQUEST;
            $data = array_merge($data, json_decode(file_get_contents('php://input'), true) ?? []);
            error_log(json_encode($data));
            $rules = $this->rules["$method $uri"];

            foreach ($rules as $field => $rule) {
                error_log("Field: $field, Rule: " . json_encode($rule) . ', Empty:' . empty($data[$field]));
                if (((! isset($data[$field])) || empty($data[$field])) && $rule['required']) {
                    sendError("Missing required field: $field", 400);

                    return false;
                }
            }
        }

        return true;
    }
}

class MiddlewareHandler
{
    private array $middlewares = [];

    public function add(Middleware $middleware): MiddlewareHandler
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    public function handle(): bool
    {
        foreach ($this->middlewares as $middleware) {
            if (! $middleware->handle()) {
                return false;
            }
        }

        return true;
    }
}
