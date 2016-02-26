<?php

class SoapSmsGateWay
{
    public function sendSms($messagesData)
    {
        ob_start();
        var_dump($messagesData);
        $result = ob_get_clean();
        file_put_contents("pars.txt", $result);


        //file_put_contents("pars.txt", serialize($messagesData->messageList->message->Struct));

        $rawPost = "Input:\r\n";
        $rawPost .= file_get_contents('php://input');
        $rawPost .= "\r\n---\r\nmessageData:\r\n";
        $rawPost .= serialize($messagesData);
        file_put_contents("log.txt", $rawPost);

        return array("status" => "true");
    }
} 