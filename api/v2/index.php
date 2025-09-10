<?php

header('Content-Type: application/json');

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove a barra inicial e divide a URL em partes
$uri_parts = explode('/', trim($request_uri, '/'));

// O primeiro item geralmente é a versão da API, o segundo é o recurso (ex: produtos)
$recurso = isset($uri_parts[4]) ? $uri_parts[4] : '';
var_dump($uri_parts);

// Roteamento
switch ($recurso) {
    case 'usuario':
        // Rota para a API de usuarios
        include 'usuario/listar.php';
        echo json_encode(['message' => 'caiu nos usuarios']);
        break;

    case 'clientes':
        // // Rota para a API de clientes
        // if ($request_method === 'GET' && isset($uri_parts[2]) && $uri_parts[2] === 'listar') {
        //     include 'clientes/listar.php';
        // } else {
        //     http_response_code(404);
        //     echo json_encode(['erro' => 'Recurso não encontrado.']);
        // }
        echo json_encode(['message' => 'caiu nos clientes']);
        break;

    default:
        // Rota padrão para um recurso desconhecido
        http_response_code(404);
        echo json_encode(['erro' => 'Recurso nao encontrado.']);
        break;
}

?>