<?php

class AuthController{
    private $middleware;

    public function __construct()
    {
     $this->middleware = new AuthMiddleware;   
    }

    public function show(Request $request, Response $response){



        $data = $this->middleware->authenticate($request, $response);
        if($data){
            $response->send(200, 'Authorized', [
                "username" => $data->usrnm,
                "name" => $data->name,
                "created at" => date('m/d/Y H:i:s', $data->created),
                "expire" => date('m/d/Y H:i:s', $data->exp),

            ]);
        }


        return $response;
    }


    
}