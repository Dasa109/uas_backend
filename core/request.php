<?php

class Request{
private $body;
private $params;

public function __construct($params){
    $this->params = $params;
    var_dump($params);

    $inputJSON = file_get_contents('php://input');
    $this->body = json_decode($inputJSON, TRUE) ?? [];
}

public function getBody(){
    return $this->body;
}

public function getParams(){
    return $this->params;
}


}

?>