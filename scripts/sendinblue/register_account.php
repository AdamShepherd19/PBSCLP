<?php
require_once('../../includes/vendor/autoload.php');

$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('testkey', '');

$apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
    new GuzzleHttp\Client(),
    $config
);
$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
$sendSmtpEmail['subject'] = 'My {{params.subject}}';
$sendSmtpEmail['htmlContent'] = '<html><body><h1>This is a transactional email {{params.parameter}}</h1></body></html>';
$sendSmtpEmail['sender'] = array('name' => 'donald-pbsuk', 'email' => 'dmartin@pbsuk.org');
$sendSmtpEmail['to'] = array(
    array('email' => 'djm@martell-scot.co.uk', 'name' => 'Donald-martell')
);
// $sendSmtpEmail['cc'] = array(
//     array('email' => 'example2@example2.com', 'name' => 'Janice Doe')
// );
// $sendSmtpEmail['bcc'] = array(
//     array('email' => 'example@example.com', 'name' => 'John Doe')
// );
$sendSmtpEmail['replyTo'] = array('email' => 'passwordreset@pbsclp.info', 'name' => 'pbsuk');
$sendSmtpEmail['headers'] = array('Some-Custom-Name' => 'unique-id-1234');
$sendSmtpEmail['params'] = array('parameter' => 'My param value', 'subject' => 'New Subject');

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
}
?>