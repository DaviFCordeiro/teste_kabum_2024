<?php
//Camada de interação com o banco de dados

require_once "../entities/Cliente.php";
class ClienteRepository {
    private $dbConnection;

    public function __construct(DatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getAllClientes(): array
    {
        // Lógica de paginação visando escalabilidade
        $rowsPerPage = 10;
        
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $rowsPerPage;

        $sqlQuery = 'SELECT * FROM clientes LIMIT :offset, :rowsPerPage;';
        $stmt = $this->dbConnection->getConnection()->prepare($sqlQuery);

        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $listaClientes = [];

        foreach ($result as $cliente) {
            $listaClientes[] = new Cliente(
                $cliente['id'],
                $cliente['nome'],
                new \DateTimeImmutable($cliente['data_nascimento']),
                $cliente['cpf'],
                $cliente['rg'],
                $cliente['telefone'],
            );
        }

        return $listaClientes;
    }

    public function findByCPF(string $cpf): ?array
    {
        $query = 'SELECT * FROM clientes WHERE cpf LIKE :cpf';
        $stmt = $this->dbConnection->getConnection()->prepare($query);
        $stmt->execute([':cpf' => '%' . $cpf . '%']);

        $result = $stmt->fetchAll();

        if (!$result) {
            return null; 
        }

        $listaClientes = [];

        foreach ($result as $cliente) {
            $listaClientes[] = new Cliente(
                $cliente['id'],
                $cliente['nome'],
                new \DateTimeImmutable($cliente['data_nascimento']),
                $cliente['cpf'],
                $cliente['rg'],
                $cliente['telefone'],
            );
        }

        return $listaClientes;
    }


    public function countClientes(): int
    {
        //Método count para exibição da tabela e paginação
        $sqlQuery = 'SELECT COUNT(*) FROM clientes';
        $stmt = $this->dbConnection->getConnection()->prepare($sqlQuery);

        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count;
    }

    public function findById(int $id): array
    {
        //Método find, utilizado para a edição de dados
        $stmt = $this->dbConnection->getConnection()->prepare('SELECT clientes.*, 
        enderecos.endereco,
        enderecos.numero_complemento,
        enderecos.cep,
        enderecos.bairro,
        enderecos.cidade,
        enderecos.estado,
        enderecos.id AS endereco_id 
        FROM clientes 
        JOIN enderecos ON clientes.id = enderecos.cliente_id 
        WHERE clientes.id = ?;');

        $stmt->bindParam(1, $id, PDO::PARAM_INT);        

        $stmt->execute();

        $result = $stmt->fetchAll();
        
        return $result;
    }

    public function remove(int $id): bool
    {
        //Método para deletar usuario do banco com base no seu ID
        $stmt = $this->dbConnection->getConnection()->prepare(
        'DELETE FROM enderecos where cliente_id = ?; 
        DELETE FROM clientes WHERE id = ?;');       

        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->bindValue(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function insert(Cliente $cliente): bool
    //Método insert que insere um novo usuário e o(s) seu(s) endereço(s)
    {    
    $this->dbConnection->getConnection()->beginTransaction();

    try {
        // Insert client information
        $insertClientQuery = 'INSERT INTO clientes (nome, data_nascimento, cpf, rg, telefone) 
        VALUES (:nome, :data_nascimento, :cpf, :rg, :telefone)';
        $stmt = $this->dbConnection->getConnection()->prepare($insertClientQuery);

        if ($stmt === false) {
            throw new \RuntimeException('Erro ao inserir cliente.');
        }

        $success = $stmt->execute([
            ':nome' => $cliente->nome(),
            ':data_nascimento' => $cliente->data_nascimento()->format('Y-m-d'),
            ':cpf' => $cliente->cpf(),
            ':rg' => $cliente->rg(),
            ':telefone' => $cliente->telefone(),
        ]);

        if (!$success) {
            throw new \RuntimeException('Erro ao inserir cliente.');
        }

        // Utilizando o ultimo id inserido no banco
        $clienteId = $this->dbConnection->getConnection()->lastInsertId();

        // Inserindo endereços
        foreach ($cliente->enderecos() as $endereco) {
            $insertAddressQuery = 'INSERT INTO enderecos (cliente_id, endereco, numero_complemento, cep, bairro, cidade, estado) 
            VALUES (:cliente_id, :endereco, :numero_complemento, :cep, :bairro, :cidade, :estado)';

            $stmt = $this->dbConnection->getConnection()->prepare($insertAddressQuery);

            if ($stmt === false) {
                throw new \RuntimeException('Erro ao inserir endereço.');
            }

            $success = $stmt->execute([
                ':cliente_id' => $clienteId,
                ':endereco' => $endereco->endereco(),
                ':numero_complemento' => $endereco->numero_complemento(),
                ':cep' => $endereco->cep(),
                ':bairro' => $endereco->bairro(),
                ':cidade' => $endereco->cidade(),
                ':estado' => $endereco->estado(),
            ]);

            if (!$success) {
                throw new \RuntimeException('Erro ao inserir endereço.');
            }
        }

        // Commit the transaction
        $this->dbConnection->getConnection()->commit();

        return true;
    } catch (\Exception $e) {
        // Rollback na transação em caso de falha
        $this->dbConnection->getConnection()->rollback();
        return false;
    }
}


public function update(Cliente $cliente): bool
//Método update que atualiza um usuário já existente e seus respectivos endereços
{
    $this->dbConnection->getConnection()->beginTransaction();

    try {
        // Atualizando informações do cliente
        $updateClientQuery = 'UPDATE clientes SET nome = :nome, data_nascimento = :data_nascimento, cpf = :cpf, rg = :rg, telefone = :telefone WHERE id = :id';
        $stmt = $this->dbConnection->getConnection()->prepare($updateClientQuery);

        if ($stmt === false) {
            throw new \RuntimeException('Erro ao atualizar cliente.');
        }

        $success = $stmt->execute([
            ':nome' => $cliente->nome(),
            ':data_nascimento' => $cliente->data_nascimento()->format('Y-m-d'),
            ':cpf' => $cliente->cpf(),
            ':rg' => $cliente->rg(),
            ':telefone' => $cliente->telefone(),
            ':id' => $cliente->id(),
        ]);        

        if (!$success) {
            throw new \RuntimeException('Erro ao atualizar cliente.');
        }        

        //Estamos deletando e inserindo os dados de endereço visando otimizar futuras manutenções
        // Deletando endereços existentes do usuario
        $deleteAddressQuery = 'DELETE FROM enderecos WHERE cliente_id = :cliente_id';
        $stmt = $this->dbConnection->getConnection()->prepare($deleteAddressQuery);

        if ($stmt === false) {
            throw new \RuntimeException('Erro ao atualizar endereços.');
        }

        $success = $stmt->execute([
            ':cliente_id' => $cliente->id(),
        ]);

        if (!$success) {
            throw new \RuntimeException('Erro ao atualizar endereços.');
        }        

        // Inserindo novos endereços
        foreach ($cliente->enderecos() as $endereco) {            
            $insertAddressQuery = 'INSERT INTO enderecos (cliente_id, endereco, numero_complemento, cep, bairro, cidade, estado) VALUES (:cliente_id, :endereco, :numero_complemento, :cep, :bairro, :cidade, :estado)';
            $stmt = $this->dbConnection->getConnection()->prepare($insertAddressQuery);

            if ($stmt === false) {
                throw new \RuntimeException('Erro ao inserir endereço.');
            }

            $success = $stmt->execute([
                ':cliente_id' => $cliente->id(),
                ':endereco' => $endereco->endereco(),
                ':numero_complemento' => $endereco->numero_complemento(),
                ':cep' => $endereco->cep(),
                ':bairro' => $endereco->bairro(),
                ':cidade' => $endereco->cidade(),
                ':estado' => $endereco->estado(),
            ]);            

            if (!$success) {                
                throw new \RuntimeException('Erro ao inserir endereço.');
            }
        }               
        // Commit the transaction
        $this->dbConnection->getConnection()->commit();

        return true;
    } catch (\Exception $e) {
        // Rollback em caso de falha     
        $this->dbConnection->getConnection()->rollback();
        return false;
    }
}

        
}
