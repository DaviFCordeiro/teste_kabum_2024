<?php
session_start();
// Checando se o usuário está logado

if (!isset ($_SESSION['username'])) {
    // Se não estiver logado, manda para a tela de login
    header("Location: ../../index.html");
    exit();
}

?>

<?php
require_once "../persistence/DatabaseConnection.php";
require_once "../repositories/ClienteRepository.php";
require_once "../services/ClienteService.php";

$dbConnection = new DatabaseConnection();

// Inicializando ClienteRepository e ClienteService
$clienteRepository = new ClienteRepository($dbConnection);
$clienteService = new ClienteService($clienteRepository);

// Get all clientes e qtd total de clientes para paginação
$clientes = isset ($_GET['searchCpf']) ? $clienteService->findByCpf($_GET['searchCpf']) : $clienteService->buscaTodos();

$totalClientes = $clienteService->countClientes();

// Variaveis para construção da interface de paginação
$page = isset ($_GET['page']) ? $_GET['page'] : 1;
$totalPages = ceil($totalClientes / 10);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
</head>

<body class="bg-white">
    <div style="padding: 0px 15px 30px 15px;" class="container mx-auto mt-8 w-full">
        <h1 class="text-2xl font-bold mb-4">Seja bem vindo,
            <?php echo $_SESSION['username']; ?>!
        </h1>

        <div class="flex">
            <div style="max-height: 220px;margin: 65px 30px;" class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
                <div class="flex">  
                    <div class="w-full">              
                        <div class="mb-4">
                            <label for="searchCpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Consultar por CPF</label>
                            <input type="text" id="searchCpf" name="searchCpf" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 searchCpf" required>
                        </div>                        
                        <button type="button" id="btn_consulta" class="bg-indigo-500 w-full text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Filtrar por CPF</button>                    
                    </div>
                </div>
                <div class="flex justify-center mt-2">
                    <a class="w-full" href="../pages/clienteForm.php">
                        <button type="submit"
                            class="mx-auto w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none font-medium focus:bg-indigo-600">
                            Cadastrar novo Cliente
                        </button>
                    </a>
                </div>
            </div>
            <div>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nome</th>
                            <th class="px-4 py-2">Data de Nascimento</th>
                            <th class="px-4 py-2">CPF</th>
                            <th class="px-4 py-2">RG</th>
                            <th class="px-4 py-2">Telefone</th>
                            <th class="px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="border px-4 py-2">
                                    <?php echo $cliente->nome(); ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo $cliente->data_nascimento()->format('d/m/Y'); ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo $cliente->cpf(); ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo $cliente->rg(); ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php echo $cliente->telefone(); ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <div class="flex justify-around">
                                        <!-- Edit icon -->
                                        <a href="../pages/clienteForm.php?id=<?php echo $cliente->id(); ?>">
                                            <button type="button"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-6 py-2.5 text-center mx-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Editar</button>
                                        </a>
                                        <!-- Delete icon -->
                                        <a href="../useCases/deleteCliente.php?id=<?php echo $cliente->id(); ?>"
                                            onclick="return confirm('Tem certeza que deseja deletar o cliente <?php echo $cliente->nome(); ?>?')">
                                            <button type="button"
                                                class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mx-1 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Deletar</button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Pagination links -->
                <div class="flex justify-between mt-4">
                    <div>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>"
                                class="p-3 rounded-lg hover:bg-gray-300 <?php echo $i == $page ? ' border border-gray-300 bg-gray-200' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <div>
                        <span>Exibindo
                            <?php echo count($clientes); ?> de um total de
                            <?php echo $totalClientes; ?> resultados
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("btn_consulta").addEventListener("click", function() {
            var searchCpf = document.getElementById("searchCpf").value;
            window.location.href = "dashboard.php?searchCpf=" + encodeURIComponent(searchCpf);
        });
    </script>
</body>

</html>