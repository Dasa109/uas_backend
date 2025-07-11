<?php

class Response
{

    public function send($statusCode, $data, $students)
    {
        http_response_code($statusCode);
        if($data){
            echo json_encode([$data, $students]);
        }
    }
}
