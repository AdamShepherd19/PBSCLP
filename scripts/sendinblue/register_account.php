<?php
    require_once('../../includes/vendor/autoload.php');

    $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(),$credentials);

    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
        'subject' => 'from the PHP SDK!',
        'sender' => ['name' => 'PBSclp Account Setup', 'email' => 'passwordreset@pbsclp.info'],
        'replyTo' => ['name' => 'PBSclp Account Setup', 'email' => 'passwordreset@pbsclp.info'],
        'to' => [[ 'name' => 'Adam', 'email' => 'shepherd.adam.2000@gmail.com']],
        'htmlContent' => '<html><body><h1>This is a transactional email {{params.bodyMessage}}</h1></body></html>',
        'params' => ['bodyMessage' => 'made just for you! (sendinblue api test)']
    ]);

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        print_r($result);
    } catch (Exception $e) {
        echo $e->getMessage(),PHP_EOL;
    }
?>