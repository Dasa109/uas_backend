<?php

class Router{
    private $routes = [];

    public function add($method, $path, $handler){
        $this->routes[] =  ["method"=>$method, "path"=>$path, "handler"=>$handler];

    }

    public function handleRequest(){
        $routes = $this->routes;
        $url = $_SERVER['REQUEST_URI'];
        $path = parse_url($url, PHP_URL_PATH);
        foreach($routes as $route){
            if($this->matchRoute($route, $path)){
                $response = new Response();
                $params = $this->getParam($route, $path);
                $request = new Request($params);
                [$controllName, $controllerMethod] = $route["handler"];
                $controller = new $controllName();
                $controller->$controllerMethod($request, $response); 
            }
        }
    }

    private function matchRoute($route, $requestPath){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != $route['method']){
            return false;
        }

        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['path']);
        $pattern ="#^".$pattern."$#";
        return preg_match($pattern, $requestPath);
    }

    private function getParam($route, $requestPath){
        preg_match_all('/\{([^}]+)\}/', $route["path"], $paramNames);

        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['path']);
        $pattern ="#^".$pattern."$#";

        $result = [];

        
        if(preg_match($pattern, $requestPath, $values)){
            array_shift($values);
            for($i=0; $i < count($paramNames[1]); $i++){
                $result = [$paramNames[1][$i] => $values[$i]];
            }
        }
        
        // var_dump($result);
        return $result;
    }
}



?>