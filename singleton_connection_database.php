<?php

use mysqli;
use Exception;
use RuntimeException;

/**
 * Classe Connection para gerenciar a conexão com o banco de dados.
 * Implementa o padrão Singleton para garantir que apenas uma instância
 * da conexão seja criada durante o ciclo de vida da aplicação.
 */

class Connection {

    // Definindo constantes para as credenciais do banco de dados
    private const HOST = "localhost";
    private const USER = "root";
    private const PASS = "";
    private const DB   = "test";

    public static ?self $instance = null;
    private mysqli $conn;

    private function __construct() {
        $this->conn = new mysqli(
            self::HOST,
            self::USER,
            self::PASS,
            self::DB
        );

        if ($this->conn->connect_error) {
            throw new RuntimeException("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    /**
     * Retorna uma instância única da class Connection.
     * Se a instância já existir, retorna a instância existente.
     * 
     * @return self
     * @throws RuntimeException
     * @throws Exception
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão mysqli.
     * 
     * @return mysqli
     * @throws Exception
     */
    public function getConnection(): mysqli {
        if ($this->conn === null) {
            throw new Exception("Conexão não estabelecida.");
        }

        return $this->conn;
    }

    /**
     * Fecha a conexão com o banco de dados.
     * 
     * @return void
     */
    public function close(): void
    {
        if ($this->conn) {
            $this->conn->close();
        }
        self::$instance = null;
    }

    // Previne a clonagem da instância
    private function __clone() {}

    // Previne a desserialização da instância
    public function __wakeup() {}
}
