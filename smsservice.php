<?php

header("Content-Type: text/xml; charset=utf-8");
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

/**
 * Пути по-умолчанию для поиска файлов
 */
set_include_path(get_include_path()
    . PATH_SEPARATOR . 'classes'
    . PATH_SEPARATOR . 'objects');

/**
 * Путь к конфигурационному файлу
 */
const CONF_NAME = "config.ini";

/**
 ** Функция для автозагрузки необходимых классов
 */
function __autoload($class_name)
{
    include $class_name . '.class.php';
}

ini_set("soap.wsdl_cache_enabled", "0"); // отключаем кеширование WSDL-файла для тестирования

//Создаем новый SOAP-сервер
$server = new SoapServer("http://{$_SERVER['HTTP_HOST']}/smsservice.wsdl.php");
//Регистрируем класс обработчик
$server->setClass("SoapSmsGateWay");
//Запускаем сервер
$server->handle();

class SoapSmsGateWay
{
    public $status;

    public $messageList;

    public function sendSms($req)
    {
        //file_put_contents('zzz.txt', printf($req));
        $this->status = true;
        $this->messageList = (object)$req->messageList;
        foreach ($this->messageList as $message) {
            ob_start();
            var_dump($message);
            file_put_contents('zzz.txt', ob_get_contents() . '\n', FILE_APPEND);
        }

        return $this;
    }
}