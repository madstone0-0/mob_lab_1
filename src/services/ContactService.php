<?php

require_once __DIR__ . '/../db/db.php';
require_once __DIR__ . '/../utils.php';

class ContactService
{

    private function doesContactExist($id)
    {
        global $db;
        $query = <<<SQL
select
    c.pid
from contacts c
where
    c.pid = :pid;
SQL;
        $stmt = $db->prepare($query);
        $stmt->bindParam(":pid", $id);
        if (! $stmt->execute()) {
            return false;
        }
        $result = $stmt->fetch();
        return $result !== false;
    }

    public function All()
    {
        global $db;
        $query = <<<SQL
select 
    c.pid,
    c.pname,
    c.pphone
from contacts c;
SQL;

        $stmt = $db->prepare($query);
        if (! $stmt->execute()) {
            return [
                'status' => 500,
                'data' => 'Failed to fetch contacts',
            ];
        }

        $result = $stmt->fetchAll();

        return [
            'status' => 200,
            'data' => $result,
        ];
    }

    public function Add($fullname, $phonenumber)
    {
        global $db;
        $query = <<<SQL
insert
into contacts
    (pname, pphone)
values (
    :pname,
    :ppnum
);
SQL;
        $stmt = $db->prepare($query);
        $stmt->bindParam(":pname", $fullname);
        $stmt->bindParam(":ppnum", $phonenumber);

        if (!$stmt->execute()) {
            return [
                "status" => 500,
                "data" => "Failed to add contact",
            ];
        }

        return [
            "status" => 200,
            "data" => "Contact added successfully",
        ];
    }

    public function One($id)
    {
        if (!$this->doesContactExist($id)) {
            return [
                "status" => 404,
                "data" => "Contact not found",
            ];
        }

        global $db;
        $query = <<<SQL
select 
    c.pid,
    c.pname,
    c.pphone
from
    contacts c
where
    c.pid = :pid;
SQL;

        $stmt = $db->prepare($query);
        $stmt->bindParam(":pid", $id);
        if (!$stmt->execute()) {
            return [
                "status" => 500,
                "data" => "Failed to fetch contact"
            ];
        }

        return [
            "status" => 200,
            "data" => $stmt->fetch()
        ];
    }

    public function Delete($id)
    {
        if (!$this->doesContactExist($id)) {
            return [
                "status" => 404,
                "data" => "Contact not found",
            ];
        }

        global $db;
        $query = <<<SQL
delete
from
    contacts
where
    pid = :pid;
SQL;

        $stmt = $db->prepare($query);
        $stmt->bindParam(":pid", $id);

        if (!$stmt->execute()) {
            return [
                "status" => 500,
                "data" => "Failed to delete contact",
            ];
        }

        return [
            "status" => 200,
            "data" => "Successfully deleted contact",
        ];
    }

    public function Update($id, $fullname, $phonenumber)
    {
        if (!$this->doesContactExist($id)) {
            return [
                "status" => 404,
                "data" => "Contact not found",
            ];
        }

        global $db;
        $query = <<<SQL
update
    contacts
set
    pname = :pname,
    pphone = :pphone
where
    pid = :pid;
SQL;
        $stmt = $db->prepare($query);
        $stmt->bindParam(":pid", $id);
        $stmt->bindParam(":pname", $fullname);
        $stmt->bindParam(":pphone", $phonenumber);

        if (!$stmt->execute()) {
            return [
                "status" => 500,
                "data" => "Failed to update contact"
            ];
        }

        return [
            "status" => 200,
            "data" => "Successfully updated contact"
        ];
    }
}
