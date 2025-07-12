<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler
{
    private $secret = "inginmenjadiprogrammerhandalnamunmalasngoding";

    public function encode($data)
    {
        $payload = [
            'created' => time(),
            'exp' => time() + (60*60), 
            'usrnm' => $data['username'],
            'pass' => $data['password'],
            'name' => $data['name']
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode($token)
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}