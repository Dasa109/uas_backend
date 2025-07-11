<?php

header('Content-Type: application/json');

spl_autoload_register(function ($class) {

    $directories = [
        __DIR__ . '/../core/',
        __DIR__ . '/../controller/',
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

// $studentStorage = new StudentStorage();
$router = new Router();
$router->add(
    'GET',
    '/api/student',
    [StudentController::class, 'getAllStudents']
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
