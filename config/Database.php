<?php

namespace app\config;

class Database{
  
    // specify your own database credentials
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    public $conn;
  
    // get the database connection
    public function getConnection_pdo(){
  
        $this->conn = null;
  
        try{
            $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
    public function getConnection_mysqli(){
        $this->conn = null;
         try{
            $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->db_name);
            
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
         return $this->conn;

    }
}
