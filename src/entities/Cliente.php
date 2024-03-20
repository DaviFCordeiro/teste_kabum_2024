<?php
class Cliente
{
    private ?int $id;
    private string $nome;
    private \DateTimeInterface $data_nascimento;
    private string $cpf;
    private string $rg;
    private string $telefone;
    /** @var Endereco[]  */
    private array $enderecos = [];

    public function __construct(?int $id, string $nome, \DateTimeInterface $data_nascimento, string $cpf, string $rg, string $telefone)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->data_nascimento = $data_nascimento;
        $this->cpf = $cpf;
        $this->rg = $rg;
        $this->telefone = $telefone;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function nome(): string
    {
        return $this->nome;
    }

    public function data_nascimento(): \DateTimeInterface
    {
        return $this->data_nascimento;
    }

    public function cpf(): string
    {
        return $this->cpf;
    }

    public function rg(): string
    {
        return $this->rg;
    }

    public function telefone(): string
    {
        return $this->telefone;
    }

    public function addEndereco(Endereco $endereco): void
    {
        $this->enderecos[] = $endereco;
    }

    /** @return Endereco[] */
    public function enderecos(): array
    {
        return $this->enderecos;
    }
}
