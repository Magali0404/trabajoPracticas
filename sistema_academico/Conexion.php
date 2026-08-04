<?php
class Conexion {
    private $host ='localhost';
    private $db = 'sistema_academico';
    private $user ='root';
    private $pass = '';
    public $conn;

    public function conectar() {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        return $this->conn;
    } 
}
?>