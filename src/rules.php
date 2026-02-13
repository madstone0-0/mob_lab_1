<?php
// Validation rules for the API routes
// The keys of the array are the HTTP method and URI 
// and the values are arrays of field names and their validation rules
define('RULES', [
    "GET /contacts/one" => ["pid" => ["required" => true],],
    "POST /contacts/add" => ["pname" => ["required" => true], "pphone" => ["required" => true]],
    "PUT /contacts/update" => ["pname" => ["required" => true], "pphone" => ["required" => true], "pid" => ["required" => true],],
    "DELETE /contacts/delete" => ["pid" => ["required" => true],]
]);
