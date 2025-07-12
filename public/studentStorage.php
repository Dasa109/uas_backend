<?php 


class StudentStorage{
    public $connection;
    //ubah sesuai database kalian
    private $servername = "localhost";
    private $username = "root";
    private $password = "Blo0t!";

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->servername;dbname=uas", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected to database successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}