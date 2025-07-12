<?php

class LoginController
{
    private $conn;

    public function __construct()
    {
        $studentStorage = new StudentStorage;
        $this->conn = $studentStorage->connection;
    }


    public function login(Request $request, Response $response)
    {
        $body = $request->getBody();

        if(!$body || empty($body['password']) || empty($body['username'])){
            $response->send(400, "EmptyInput", ["Username and password is required"]);
            return $response;
        }

        $usrnm = $body['username'];
        $pass = $body['password'];

        $sql = "SELECT * FROM admin WHERE username = '$usrnm'";

        try {
            $result = $this->conn->prepare($sql);
            $result->execute();
            $admin = $result->fetch(PDO::FETCH_ASSOC);
            if ($admin) {
                if ($admin['username'] == $usrnm && $admin['password'] == $pass) {
                    $jwtHandler = new JwtHandler();
                    $token = $jwtHandler->encode([
                        'username' => $admin['username'],
                        'password' => $admin['password'],
                        'name' => $admin['nama']
                    ]);
                    $response->send(200, "Login successful", ["token" => $token]);
                } else {
                    $response->send(401, 'WrongCredential', 'Password or username is incorrect');
                }
            } else {
                $response->send(404, 'NotFound', 'User not found');
            }
            return $response;
        } catch (\Throwable $th) {
            $response->send(500, "Server error", $th->getMessage());
            throw $th;
        }
    }
}
