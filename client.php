<?php
/**
 * /client.php
 */
header("Content-Type: text/html; charset=utf-8");
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));
/**
 * Пути по-умолчанию для поиска файлов
 */
set_include_path(get_include_path()
    . PATH_SEPARATOR . 'classes'
    . PATH_SEPARATOR . 'objects');
/**
 ** Функция для автозагрузки необходимых классов
 */
function __autoload($class_name)
{
    include $class_name . '.class.php';
}

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

// Заготовки объектов
class Message
{
    public $phone;
    public $text;
    public $date;
    public $type;
}

class MessageList
{
    public $message;
}

class Request
{
    public $messageList;
}

// создаем объект для отправки на сервер
$msg1 = new Message();
$msg1->phone = '79871234567';
$msg1->text = 'Тестовое сообщение 1';
$msg1->date = '2013-07-21T15:00:00.26';
$msg1->type = 15;

$msg2 = new Message();
$msg2->phone = '79871234567';
$msg2->text = 'Тестовое сообщение 2';
$msg2->date = '2014-08-22T16:01:10';
$msg2->type = 16;

$msg3 = new Message();
$msg3->phone = '79871234567';
$msg3->text = 'Тестовое сообщение 3';
$msg3->date = '2014-08-22T16:01:10';
$msg3->type = 17;

$messageList = new MessageList();
$messageList->message[] = $msg1;
$messageList->message[] = $msg2;

$req = new Request();
$req->messageList = $messageList;
//----------------------------
$namespace = "";
$request = new stdClass();
$messageList = new ArrayObject();
$messageAttributes = new ArrayObject();

$messageAttributes[0]->phone = '79871234567';
$messageAttributes[0]->text = 'Test message 1';
$messageAttributes[0]->date = '2013-07-21T15:00:00.26';
$messageAttributes[0]->type = 15;

$messageAttributes[1]->phone = new SoapVar("79871234567", XSD_STRING, NULL, $namespace, 'phone', $namespace);
$messageAttributes[1]->text = new SoapVar("Test message 2", XSD_STRING, NULL, $namespace, 'text', $namespace);
$messageAttributes[1]->date = new SoapVar("2013-07-21T15:00:00.26", XSD_STRING, NULL, $namespace, 'date', $namespace);
$messageAttributes[1]->type = new SoapVar(11, XSD_DECIMAL, NULL, $namespace, 'type', $namespace);

var_dump($messageAttributes);

$message0 = new SoapVar($messageAttributes[0], SOAP_ENC_OBJECT, NULL, $namespace, 'message', $namespace);
$message1 = new SoapVar($messageAttributes[1], SOAP_ENC_OBJECT, NULL, $namespace, 'message', $namespace);

$messageList->append($message0);
$messageList->append($message1);

$request->messageList = new SoapVar($messageList, SOAP_ENC_OBJECT, NULL, $namespace, 'messageList', $namespace);

//$req->messageList = (object)$req->messageList;
var_dump($request);
//var_dump(unserialize('O:8:"stdClass":1:{s:11:"messageList";O:8:"stdClass":1:{s:7:"message";O:8:"stdClass":1:{s:6:"Struct";a:2:{i:0;O:8:"stdClass":4:{s:5:"phone";s:11:"79871234567";s:4:"text";s:37:"Тестовое сообщение 1";s:4:"date";s:22:"2013-07-21T15:00:00.26";s:4:"type";s:2:"15";}i:1;O:8:"stdClass":4:{s:5:"phone";s:11:"79871234567";s:4:"text";s:37:"Тестовое сообщение 2";s:4:"date";s:19:"2014-08-22T16:01:10";s:4:"type";s:2:"16";}}}}}'));
$uri = "http://{$_SERVER['HTTP_HOST']}/smsservice.wsdl.php";
$client = new SoapClient($uri,
    array('soap_version' => SOAP_1_2, 'trace' => 1));
var_dump($client->sendSms($request));

file_put_contents("request.xml", $client->__getLastRequest());