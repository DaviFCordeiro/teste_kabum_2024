<?php
//Camada de validação de dados
require_once "../entities/Endereco.php";
class ClienteService {
    private $repositorioClientes;

    public function __construct(ClienteRepository $repositorioClientes) {
        $this->repositorioClientes = $repositorioClientes;
    }

    public function buscaTodos(): array
    {
        $listaClientes = $this->repositorioClientes->getAllClientes();

        return $listaClientes;
    }

    public function findByCpf(string $cpf): ?array
    {
        $cliente = $this->repositorioClientes->findByCPF($cpf);

        return $cliente;
    }


    public function find(int $id): Cliente
    {
        //Visto que para cada endereço do cliente retorna-se uma linha, fazemos o tratamento
        $result = $this->repositorioClientes->findById($id);                 
        
        $cliente = new Cliente(
            $result[0]['id'],
            $result[0]['nome'],
            new \DateTimeImmutable($result[0]['data_nascimento']),
            $result[0]['cpf'],
            $result[0]['rg'],
            $result[0]['telefone']
        );

        foreach ($result as $row) {            
            $endereco = new Endereco($row['endereco_id'], $row['endereco'], $row['numero_complemento'],$row['cep'],$row['bairro'],$row['cidade'],$row['estado']);
            $cliente->addEndereco($endereco);
        }

        return $cliente;
    }

    public function countClientes(): int
    {
        $countClientes = $this->repositorioClientes->countClientes();

        return $countClientes;
    }

    public function deleteCliente($id): bool
    {
        $result = $this->repositorioClientes->remove($id);

        return $result;
    }
    
    public function save(Cliente $cliente)
    {                
            if ($cliente->id() === null) {                          
                return $this->repositorioClientes->insert($cliente);
            }
                   
            return $this->repositorioClientes->update($cliente);              
    }
}
