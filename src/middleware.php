<?php

interface Middleware
{
    /**
     * Handle the middleware logic.
     * @return void
     */
    public function handle();
}

class JsonMiddleware implements Middleware
{
    // Set the content type to JSON
    public function handle(): bool
    {
        header('Content-Type: application/json; charset=UTF-8');

        return true;
    }
}

class ValidationMiddleware implements Middleware
{
    // Validation rules for the API
    private array $rules;

    // Initialize the middleware with the validation rules
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    // Handle the validation logic
    public function handle()
    {
        // Get the HTTP method and URI
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parseURI();
        $uri = '/' . implode('/', $uri);

        // Based on the method and URI, check if there are any validation rules defined
        // If there are, validate the request data against the rules
        // If any required fields are missing or empty, return a 400 error with a message indicating which field is missing
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
    // Array of middlewares to be executed in order
    private array $middlewares = [];

    // Add a middleware to the handler returning
    // a reference to the handler to allow chaining
    public function add(Middleware $middleware): MiddlewareHandler
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    // Execute the middlewares in order and return false if any middleware returns false
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
