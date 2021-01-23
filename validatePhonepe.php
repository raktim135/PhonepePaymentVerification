<?php

error_reporting(0);

//change these details
$allowedkey    = "Your API Key";
$gmailuser     = "Your Gmail ID";
$gmailpassword = "Your Gmail Password";
//Nothing else to change

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

//only continue if if request is POST for security reasons
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $op = json_encode(array(
        'type' => 'error',
        'msg' => 'Your browser sent a type of request that server could not understand'
    ));
    die($op);
}

if (!isset($_GET['apikey']) || empty($_GET['apikey'])) {
    $op = json_encode(array(
        'type' => 'error',
        'msg' => 'Forbidden 1 '.$_GET['apikey']
    ));
    die($op);
}
if (!isset($_GET['txnid']) || empty($_GET['txnid'])) {
    $op = json_encode(array(
        'type' => 'error',
        'msg' => 'Please enter your transaction ID.'
    ));
    die($op);
}
if (!isset($_GET['amount']) || empty($_GET['amount'])) {
    $op = json_encode(array(
        'type' => 'error',
        'msg' => 'Please enter the amount.'
    ));
    die($op);
}

$txnid  = $_GET['txnid'];
$amount = $_GET['amount'];

$onetime = '1';
if ($_GET['onetime'] == '0'){
    $onetime = '0';
}

//match API key
if ($_GET['apikey'] !== $allowedkey) {
    die(json_encode(array(
        "type" => "error",
        "msg" => "Forbidden 2"
    )));
}

//verification rules for transaction ID, for security reasons
if (strlen($txnid) < 10) {
    die(json_encode(array(
        "type" => "error",
        "msg" => "The transaction ID you entered was not found. 1"
    )));
}

if (strlen($txnid) > 50) {
    die(json_encode(array(
        "type" => "error",
        "msg" => "The transaction ID you entered was not found. 2"
    )));
}

if (!ctype_alnum($txnid)) {
    die(json_encode(array(
        "type" => "error",
        "msg" => "The transaction ID you entered was not found. 3"
    )));
}

//if you want to validate the transaction id using regex, you can do it from below
//modify the regex as per your requirements
/*if (!preg_match('/^[0-9A-Z]*([0-9][A-Z]|[A-Z][0-9])[0-9A-Z]*$/', $txnid)) {
    die(json_encode(array(
        "type" => "error",
        "msg" => "The transaction ID you entered was not found. 4"
    )));
}*/

//verification rule for amount for security reasons
if (strlen($amount) > 5) {
    die(json_encode(array(
        "type" => "error",
        "msg" => "Invalid amount."
    )));
}

?>
