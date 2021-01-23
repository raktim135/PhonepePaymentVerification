<?php
require_once 'validatePaytm.php';

$imapaddress       = "{imap.gmail.com:993/imap/ssl}";
$imapmainbox       = "INBOX";
$imapaddressandbox = $imapaddress . $imapmainbox;
$connection = imap_open($imapaddressandbox, $gmailuser, $gmailpassword) or die(json_encode(array(
    "type" => "error",
    "msg" => "Could not connect to the peer due to internal server error."
)));

//Connection established to the mail server, now search the transaction
$matchTxn = imap_search($connection, 'TEXT ' . $txnid . '"');

if ($matchTxn !== false) {

    //get message id
    $a         = var_export($matchTxn, true);
    $data      = $a;
    $whatIWant = substr($data, strpos($data, ">") + 1);
    $to        = ", )";
    $c         = chop($whatIWant, $to);
    $d         = str_replace(",", "", $c);
    $e         = preg_replace('/\s+/', '', $d);
    
    //only if the certain amount was sent
    $mailbody = imap_body($connection, $e);
    $bodyvar  = var_export($mailbody, true);

    $head = imap_headerinfo($connection, $e);


    $header = imap_headerinfo($connection, $e);
    $fromaddr = $header->from[0]->mailbox . "@" . $header->from[0]->host;

    if($fromaddr != "noreply@phonepe.com" ) {
        die(json_encode(array(
            "type" => "error",
            "msg" => "Transaction found, but not Authorize by paytm.com"
        )));
    }

    $subject = "";
    foreach ($head as $k => $v) {
        if($k == "subject"){
            $subject = $v;
        }
    }

    $substr = "Rs. ".$amount." paid";// "Rs. ".$amount." paid";
    $substr = "Received_=E2=82=B9_".$amount."_from";

    //if (!strpos($bodyvar, "$amount")) {
    if(strpos($subject, $substr) === false ) {
        die(json_encode(array(
            "type" => "error",
            "msg" => "Transaction found, but not of Rs. ".$amount
        )));
    }
    if ($onetime == '1') {
        //move the email to our saved folder
        $imapresult=imap_mail_move($connection,$e.':'.$e,'VerifiedPhonePe');
        if($imapresult==false){
            die(imap_last_error());
        }
        imap_close($mbox,CL_EXPUNGE);
    }
    die(json_encode(array(
        "type" => "success",
        "msg" => "Transaction Verified"
    )));
    
} else {
    //not paid yet, throw error
    die(json_encode(array(
        "type" => "error",
        "msg" => "The transaction ID you entered was not found."
    )));
}
?>