<?php

class Endereco {
    private ?int $id;
    private string $endereco;
    private string $numero_complemento;
    private string $cep;
    private string $bairro;
    private string $cidade;
    private string $estado;

    public function __construct(?int $id, 
    string $endereco, 
    string $numero_complemento, 
    string $cep, 
    string $bairro, 
    string $cidade, 
    string $estado
    )
    {
        $this->id = $id;
        $this->endereco = $endereco;
        $this->numero_complemento = $numero_complemento;
        $this->cep = $cep;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function endereco(): string
    {
        return $this->endereco;
    }

    public function numero_complemento(): string
    {
        return $this->numero_complemento;
    }

    public function cep(): string
    {
        return $this->cep;
    }

    public function bairro(): string
    {
        return $this->bairro;
    }

    public function cidade(): string
    {
        return $this->cidade;
    }

    public function estado(): string
    {
        return $this->estado;
    }
}