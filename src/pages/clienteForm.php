<?php
require_once "../persistence/DatabaseConnection.php";
require_once "../repositories/ClienteRepository.php";
require_once "../services/ClienteService.php";
require_once "../entities/Cliente.php";
require_once "../entities/Endereco.php";

$dbConnection = new DatabaseConnection();

// Inicializando ClienteRepository e ClienteService
$clienteRepository = new ClienteRepository($dbConnection);
$clienteService = new ClienteService($clienteRepository);

// Checando se é edição ou criação baseado no id
if (isset($_GET['id'])) {        
    $clienteId = $_GET['id'];
    $cliente = $clienteService->find($clienteId);
} else {    
    $cliente = new Cliente(null, '', new DateTime(), '', '', '');
    $cliente->addEndereco(new Endereco (null, '','','','','',''));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Função para adicionar um novo endereço
        function adicionarEndereco() {
            var container = document.getElementById("endereco-container");
            var index = container.children.length / 2;
            var addressDiv = document.createElement("div");
            addressDiv.className = "w-full max-w-lg p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8";

            var buttonDiv = document.createElement("div");
            buttonDiv.className = "flex justify-end relative";

            var removeButton = document.createElement("button");
            removeButton.innerHTML = "Excluir";
            removeButton.className = "focus:outline-none absolute text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5";
            removeButton.style.top = "-20px";
            removeButton.type = "button";            
            removeButton.addEventListener("click", function() {
                addressDiv.remove();
            });
            buttonDiv.appendChild(removeButton);

            addressDiv.appendChild(buttonDiv);            

            var fieldsDiv = document.createElement("div");
            fieldsDiv.className = "grid grid-cols-2 gap-3";

            var labels = ["Endereço", "Numero", "Bairro", "CEP", "Cidade", "Estado"];
            var names = ["endereco", "numero_complemento", "cep", "bairro", "cidade", "estado"];

            for (var i = 0; i < labels.length; i++) {
                var fieldDiv = document.createElement("div");
                var label = document.createElement("label");
                label.innerHTML = labels[i];
                label.className = "block mb-2 text-sm font-medium text-gray-900 dark:text-white";

                var input = document.createElement("input");
                input.type = "text";
                input.required = true;                
                input.id = names[i] + "_" + index;
                input.name = names[i] + "_" + index;
                input.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5";                

                fieldDiv.appendChild(label);
                fieldDiv.appendChild(input);

                fieldsDiv.appendChild(fieldDiv);
            }

            addressDiv.appendChild(fieldsDiv);
            container.appendChild(addressDiv);
        }

        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("btn-excluir")) {
                event.target.parentNode.parentNode.remove(); // Removes the parent of the parent element
            }
        });
        
    </script>
</head>
<body>
    <div class="container mx-auto mt-8 w-10/12">
        <h1 class="text-2xl font-bold mb-4">
            <?php 
                if (isset($_GET['id'])) {
                   echo 'Edição de cliente';
                } else {
                    echo 'Criação de novo cliente';
                }
            ?>
        </h1>
        <form action="../useCases/updateCliente.php" method="POST">
            <!-- Campo hidden para o Cliente ID -->
            <input type="hidden" name="clienteId" value="<?php echo $cliente->id(); ?>">

            <div class="grid grid-cols-3 gap-3">
                <div class="mb-4">
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome:</label>
                <input type="text" id="nome" name="nome" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $cliente->nome(); ?>" required>
                </div>
            
                <div class="mb-4">
                    <label for="data_nascimento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        value="<?php echo $cliente->data_nascimento()->format('Y-m-d'); ?>" required>
                </div>
            
                <div class="mb-4">
                    <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF:</label>
                    <input type="text" id="cpf" name="cpf" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        value="<?php echo $cliente->cpf(); ?>" required>
                </div>
            
                <div class="mb-4">
                    <label for="rg" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RG:</label>
                    <input type="text" id="rg" name="rg"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        value="<?php echo $cliente->rg(); ?>" required>
                </div>
            
                <div class="mb-4">
                    <label for="telefone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone (Sem DDD):</label>
                    <input type="text" id="telefone" name="telefone" maxlength="9" minlength="9"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        value="<?php echo $cliente->telefone(); ?>" required>
                </div>
            </div>
                 
            <div class="flex justify-between mb-4 mt-4">
                <h2 class="text-xl font-bold mb-4 mr-6">Endereços:</h2> 
                <button type="button" id="add-endereco-btn" onclick="adicionarEndereco()" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Adicionar novo endereço</button>
            </div>            
            <!-- Endereco fields -->
            <div id="endereco-container" class="grid grid-cols-2 gap-3">
            <?php foreach ($cliente->enderecos() as $endereco): ?>   
                <input type="hidden" name="<?php echo 'enderecoId_' . array_search($endereco, $cliente->enderecos()); ?>" value="<?php echo $endereco->id(); ?>">
                
                <div class="w-full max-w-lg p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">                                            
                    <div class="flex justify-end relative">
                        <button style="top : -20px;" type="button" class="focus:outline-none absolute text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 btn-excluir">Excluir</button>
                    </div>  
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="endereco" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Endereço</label>
                            <input type="text" id="<?php echo 'endereco' . array_search($endereco, $cliente->enderecos()); ?>"
                            name="<?php echo 'endereco_' . array_search($endereco, $cliente->enderecos()); ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="<?php echo $endereco->endereco(); ?>" required>
                        </div>
                        <div>
                            <label for="numero_complemento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Numero</label>
                            <input type="text" id="<?php echo 'numero_complemento' . array_search($endereco, $cliente->enderecos()); ?>"
                            name="<?php echo 'numero_complemento_' . array_search($endereco, $cliente->enderecos()); ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="<?php echo $endereco->numero_complemento(); ?>" required>
                        </div>
                        <div>
                            <label for="cep" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CEP</label>
                            <input type="text" id="<?php echo 'cep' . array_search($endereco, $cliente->enderecos()); ?>"
                            name="<?php echo 'cep_' . array_search($endereco, $cliente->enderecos()); ?>" maxlength="8" minlength="8"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="<?php echo $endereco->cep(); ?>" required>
                        </div>
                        <div>
                            <label for="bairro" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bairro</label>
                            <input type="text" id="<?php echo 'bairro' . array_search($endereco, $cliente->enderecos()); ?>"
                            name="<?php echo 'bairro_' . array_search($endereco, $cliente->enderecos()); ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="<?php echo $endereco->bairro(); ?>" required>
                        </div>
                        <div>
                            <label for="cidade" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cidade</label>
                            <input type="text" id="<?php echo 'cidade' . array_search($endereco, $cliente->enderecos()); ?>"
                            name="<?php echo 'cidade_' . array_search($endereco, $cliente->enderecos()); ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="<?php echo $endereco->cidade(); ?>" required>
                        </div>
                        <div>
                            <label for="estado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado</label>
                            <input type="text" id="<?php echo 'estado' . array_search($endereco, $cliente->enderecos()); ?>"
                            name="<?php echo 'estado_' . array_search($endereco, $cliente->enderecos()); ?>" maxlength="2" minlength="2"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="<?php echo $endereco->estado(); ?>" required>
                        </div>
                    </div>                                                                                                                      
                </div>                
            <?php endforeach; ?>  
            </div>                                               
            <div class="my-8 mx-auto flex justify-center">
                <button type="submit" class="w-4/12 mx-auto bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none font-medium focus:bg-indigo-600">Salvar Alterações</button>
            </div>
        </form>
    </div>        
</body>
</html>
