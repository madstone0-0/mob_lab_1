<?php

require_once __DIR__ . "/../services/ContactService.php";
require_once __DIR__ . "/../utils.php";

// Contact service with business logic
$ContactService = new ContactService;

// Route map for contacts
$contactRoutes = [
    "GET" => ["all" => all(...), "one" => one(...)],
    "POST" => ["add" => add(...)],
    "PUT" => ["update" => update(...)],
    "DELETE" => ["delete" => delete(...)],
];


// Get all contacts route
function all()
{
    global $ContactService;
    $res =  $ContactService->All();
    if (!isOk($res["status"])) {
        sendError($res["data"], $res["status"]);
        return;
    }

    sendData($res["data"]);
}

// Get one contact route
function one($data)
{
    $id = $data["pid"];

    global $ContactService;
    $res =  $ContactService->One($id);
    if (!isOk($res["status"])) {
        sendError($res["data"], $res["status"]);
        return;
    }

    sendData($res["data"]);
}

// Add contact route
function add($data)
{
    $fullname = $data["pname"];
    $phonenumber = $data["pphone"];

    global $ContactService;
    $res =  $ContactService->Add($fullname, $phonenumber);
    if (!isOk($res["status"])) {
        sendError($res["data"], $res["status"]);
        return;
    }

    sendData($res["data"]);
}

// Delete contact route
function delete($data)
{
    $id = $data["pid"];

    global $ContactService;
    $res =  $ContactService->Delete($id);
    if (!isOk($res["status"])) {
        sendError($res["data"], $res["status"]);
        return;
    }

    sendData($res["data"]);
}

// Update contact route
function update($data)
{
    $id = $data["pid"];
    $fullname = $data["pname"];
    $phonenumber = $data["pphone"];

    global $ContactService;
    $res =  $ContactService->Update($id, $fullname, $phonenumber);
    if (!isOk($res["status"])) {
        sendError($res["data"], $res["status"]);
        return;
    }

    sendData($res["data"]);
}

// Contacts route handler
// This function will be called by the main router in index.php when the URI matches "contacts"
function contactsHandler($verb, $uri)
{
    global $contactRoutes;
    routeHandler($verb, $uri, $contactRoutes);
}
