<?php

/*
The JSON request should have the form of this
{
    action: "insert",
    type: "parent",
    parent: {
        id: 1,
        firstName: "f",
        lastName: "l",
        email: "e",
        phone: "p"
    }
}
 */

// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

$action = $data->action;

if (action == "insert")

?>
