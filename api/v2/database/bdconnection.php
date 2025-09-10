<?php

$host = '200.19.1.18';
$dbname = 'thalistrisch';
$user = 'thalistrisch';
$password = '123456';
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $banco = new PDO($dsn, $user, $password);
    // Define o modo de erro para lançar exceções em caso de problemas
    $banco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão com o banco de dados bem-sucedida!";
} catch (PDOException $e) {
    // Captura e exibe o erro em caso de falha na conexão
    echo "Falha na conexão com o banco de dados: " . $e->getMessage();
    return null;
}

?>