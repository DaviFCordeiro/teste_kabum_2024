<?php
require_once "./DatabaseConnection.php";
require_once "../services/AuthenticationService.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $dbConnection = new DatabaseConnection();
    $authenticationService = new AuthenticationService($dbConnection);

    if ($authenticationService->authenticate($username, $password)) {
        // Armazenando usuario autenticado na sessão
        session_start();
        $_SESSION['username'] = $username;
        // Redirecionando para o dashboard
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        // Redirecionando de volta para a tela de login com flag de erro
        header("Location: ../../index.html?error=1");
        exit();
    }
}