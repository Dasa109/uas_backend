<?php

class AuthMiddleware
{
    public function authenticate(Request $request, Response $response)
    {
        $headers = getallheaders();

        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader)) {
            $response->send(401, "Unauthorized", "No token provided");
            return false;
        }

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $res)) {
            $response->send(401, "InvalidToken", ["Token is invalid format"]);
            return false;
        }

        $token = $res[1];
        $jwtHandler = new JwtHandler();

        try {
            $decoded = $jwtHandler->decode($token);
            return $decoded;
        } catch (Exception $e) {
            $response->send(401, "Unauthorized", $e->getMessage());
            return false;
        }
    }
}
