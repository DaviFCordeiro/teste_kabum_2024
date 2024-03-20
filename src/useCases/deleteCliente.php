<?php
require_once "../persistence/DatabaseConnection.php";
require_once "../repositories/ClienteRepository.php";
require_once "../services/ClienteService.php";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $dbConnection = new DatabaseConnection();

    // Inicializando ClienteRepository e ClienteService
    $clienteRepository = new ClienteRepository($dbConnection);
    $clienteService = new ClienteService($clienteRepository);
    
    // Get the cliente ID from the query parameter
    $clienteId = $_GET["id"];
    
    // Call the delete method from ClienteService
    $clienteService->deleteCliente($clienteId);

    header("Location: ../pages/dashboard.php");
    exit;
}
