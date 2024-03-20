<?php
require_once "../persistence/DatabaseConnection.php";
require_once "../repositories/ClienteRepository.php";
require_once "../services/ClienteService.php";
require_once "../entities/Cliente.php";
require_once "../entities/Endereco.php";


try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data = $_POST;            
    
        $cliente = new Cliente(     
                $data['clienteId'] != null ? $data['clienteId'] : null,       
                $data['nome'],
                new \DateTimeImmutable($data['data_nascimento']),
                $data['cpf'],
                $data['rg'],
                $data['telefone']
            );
        
        // Aqui estamos extraindo os dados de endereço vindos do post
            foreach ($data as $key => $value) {    
            //Verificando novo objeto a partir do atribute enderecoId_ ou endereco_     
                if (strpos($key, 'endereco_') !== false && (strpos($key, 'enderecoId_') !== false || $value !== '')) {
                    // Extract index from the key
                    $index = substr($key, strrpos($key, '_') + 1);
                                        
                    $cliente->addEndereco(new Endereco(
                        isset($data["enderecoId_$index"]) && 
                        $data["enderecoId_$index"] != "" ? $data["enderecoId_$index"] : null,
                        $data["endereco_$index"],
                        $data["numero_complemento_$index"],
                        $data["cep_$index"],
                        $data["bairro_$index"],
                        $data["cidade_$index"],
                        $data["estado_$index"]
                    )) ;                                              
                }
            }  
            
        $dbConnection = new DatabaseConnection();
    
        // Inicializando ClienteRepository e ClienteService
        $clienteRepository = new ClienteRepository($dbConnection);
        $clienteService = new ClienteService($clienteRepository);        
        
         // Invocando o método save para atualizar o banco de dados
         $sucesso = $clienteService->save($cliente);   
     
         header("Location: ../pages/dashboard.php");
         exit;
    }
} catch (\Throwable $th) {
    throw $th;
}
