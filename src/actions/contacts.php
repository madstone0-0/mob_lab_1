<?php

require_once __DIR__ . "/../services/ContactService.php";
require_once __DIR__ . "/../utils.php";

$ContactService = new ContactService;

$contactRoutes = [
    "GET" => ["all" => all(...), "one" => one(...)],
    "POST" => ["add" => add(...)],
    "PUT" => ["update" => update(...)],
    "DELETE" => ["delete" => delete(...)],
];


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


function contactsHandler($verb, $uri)
{
    global $contactRoutes;
    routeHandler($verb, $uri, $contactRoutes);
}
