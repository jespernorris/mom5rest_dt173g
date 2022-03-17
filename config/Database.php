<?php
class Database{
    private $host = "studentmysql.miun.se";
    private $db_name = "jeno2011";
    private $username = "jeno2011";
    private $password = "sZ!c2VSXqC";
    public $conn;
  
    // ansluta till databasen med uppgifter angivna ovan
    public function connect(){
  
        $this->conn = null;
  
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    // stänga anslutning till databas
    public function close(){
        $this->conn = null;
    }
}