<?php
define('RULES', [
    "GET /contacts/one" => ["pid" => ["required" => true],],
    "POST /contacts/add" => ["pname" => ["required" => true], "pphone" => ["required" => true]],
    "PUT /contacts/update" => ["pname" => ["required" => true], "pphone" => ["required" => true], "pid" => ["required" => true],],
    "DELETE /contacts/delete" => ["pid" => ["required" => true],]
]);
