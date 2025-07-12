<?php

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

spl_autoload_register(function ($class) {

    $directories = [
        __DIR__ . '/../core/',
        __DIR__ . '/../controller/',
        __DIR__ . '/../middleware/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . strtolower($class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/studentStorage.php';

$router = new Router();
$router->add('POST', '/api/auth/login', [LoginController::class, 'login']);
$router->add('GET', '/api/auth/me', [AuthController::class, 'show']);

$router->add(
    'GET',
    '/api/students',
    [StudentController::class, 'getAllStudents']
);
$router->add(
    'GET',
    '/api/student/{nim}',
    [StudentController::class, 'getOneStudent']
);

$router->add(
    'POST',
    '/api/student/create',
    [StudentController::class, 'addStudent']
);
$router->add(
    'PUT',
    '/api/student/update/{nim}',
    [StudentController::class, 'updateStudent']
);
$router->add(
    'DELETE',
    '/api/student/delete/{nim}',
    [StudentController::class, 'deleteStudent']
);

$router->handleRequest();
