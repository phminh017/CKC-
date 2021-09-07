<?php
class Connection
{
    public $server = 'localhost';
    public $port = 3306;
    public $dbname = 'dbname';
    public $username = 'root';
    public $password = '';

    function __construct($connCofig = [])
    {
        if (array_key_exists('server', $connCofig)) {
            $this->server = $connCofig['server'];
        }
        if (array_key_exists('port', $connCofig)) {
            $this->port = $connCofig['port'];
        }
        if (array_key_exists('dbname', $connCofig)) {
            $this->dbname = $connCofig['dbname'];
        }
        if (array_key_exists('username', $connCofig)) {
            $this->username = $connCofig['username'];
        }
        if (array_key_exists('password', $connCofig)) {
            $this->password = $connCofig['password'];
        }
    }
    function createPDOConnection()
    {
        $server = $this->server;
        $port = $this->port;
        $dbname = $this->dbname;
        $username = $this->username;
        $password = $this->password;
        $pdoConn = NULL;
        try {
            $pdoConn = new PDO("mysql:host=$server:$port;dbname=$dbname", $username, $password);
            $pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Db Connection failed: " . $e->getMessage();
        }
        return $pdoConn;
    }
    function getServer()
    {
        return $this->server;
    }
    function getPort()
    {
        return $this->port;
    }
    function getDbname()
    {
        return $this->server;
    }
    function getUsername()
    {
        return $this->username;
    }
    function getPassword()
    {
        return $this->password;
    }
}
