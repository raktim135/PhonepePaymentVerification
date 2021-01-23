# PhonepePaymentVerification
This is an unofficial PhonePe payment verification API which verifies payment status using the linked email id using PHP IMAP.

This API has been working fine with gmail. I have not tested with any other email providers.

Documentation:

To use this API, you just need to change 3 things in validatePhonepe.php file. Open it and make changes to the below:

1. Set any API key to $allowedkey
2. Add your gmail id to $gmailuser
3. Add your gmail password to $gmailpassword

Also you need to allow your gmail account to be used by this API. For that, you need to do 3 things as mentioned below:

1. Turn on less secure app access on your gmail account from https://myaccount.google.com/lesssecureapps
2. Didable captcha on your gmail account from https://accounts.google.com/b/0/DisplayUnlockCaptcha
3. Create a label within your gmail namely VerifiedPhonePe. If you don't know how to create label within gmail, you can google it.

That's all you need to configure. Then you can call this API using GET request (you can change it to any other menthod from validatePhonepe.php file) as below:
<pre>
http://example.com/api_directory/phonepe.php?apikey=111111&onetime=0&txnid=202101092123480099&amount=1402
</pre>
Here you need to pass 4 parameters, where 3 parameters are mandatory and 1 is optional.
Thease are:
1. apikey : It is mandatory and it will use to verify the request is coming from a authorize source.
2. onetime : It is optional parameter. If you pass 1 to this parameter, then if the transction found, after successfull verification, it will be moved to the VerifiedPhonePe label within your gmail and this transaction will not be verified again by the API untill and unless you move the email from VerifiedPhonePe to inbox again.
3. txnid : It is mandatory parameter. It is the transaction or order id of your Phonepe transaction.
4. amount : It is mandatory parameter. It is the transaction amount.

On successful verification, you will get the below json response:
<br>
<pre>{"type":"success","msg":"Transaction Verified"}</pre>
<br>
If any error occur, you will also get proper error message.
<br>
That's all.<br>
cheers :)
