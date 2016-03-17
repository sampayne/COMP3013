<?php


/*

s3user
Access Key ID:
AKIAJGH5MMS7WXPHMBJQ
Secret Access Key:
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


*/

    putenv('AWS_ACCESS_KEY_ID=AKIAJGH5MMS7WXPHMBJQ');
    putenv('AWS_SECRET_ACCESS_KEY=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');


    require ('aws/aws-autoloader.php');
    require ('Utility.php');
    require ('Database.php');
    require ('Request.php');
    require ('Session.php');
    require ('RequestHandler.php');
    require ('View.php');
    require ('NotificationSender.php');

    require ('creators/include.php');