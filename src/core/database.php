<?php


class Database
{

    private $db_type = DB_TYPE;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;


    public function connect(){

        $dsn = $this->db_type . ":host=" . $this->host . ";dbname=" . $this->dbname;

        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_STRINGIFY_FETCHES => false
        );
        
        return new \PDO($dsn,$this->user,$this->pass,$options);
    
    }
}