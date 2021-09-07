<?php
class Dbsession
{
    public $session_tablename = "session";
    public $session_timeout = 60; //default 180 minutes

    public $session_id;
    public $session_name;

    public $db = NULL;

    function __construct($dbsessionConfig = [])
    {
        if (array_key_exists("session_tablename", $dbsessionConfig)) {
            $this->tableName = $dbsessionConfig['session_tablename'];
        }

        if (array_key_exists("session_timeout", $dbsessionConfig)) {
            $this->session_timeout = $dbsessionConfig['session_timeout'];
        }

        $this->db = App::$db;
    }

    function open()
    {
        session_cache_expire($this->session_timeout);
        session_start();
        $this->session_id = session_id();
    }
    function close()
    {
        session_write_close();
    }
    function destroy()
    {
        //Delete row of session table
        // $tableName = $this->session_tablename;
        // $session_id = $this->session_id;
        // $sql = "DELETE FROM `$tableName` WHERE `session_id`=$session_id;";
        // App::$db->execCommand($sql);

        //destroy session
        session_destroy();
    }
    function save()
    {
        $this->data = $_SESSION;
    }
    function getDb()
    {
        return $this->db;
    }
    function getTableName()
    {
        return $this->session_tablename;
    }
    function setSessionId($sessionId)
    {
        $_SESSION['session_id'] = $sessionId;
    }
    function getSessionId()
    {
        return $_SESSION['session_id'];
    }
    function set($name, $value)
    {
        //add a variable into $_SESSION
        $_SESSION[$name] = $value;
    }
    function get($name)
    {
        //get $value of $_SESSION[$name]
        return isset($_SESSION[$name]) ? $_SESSION[$name] : 0;
    }
    function remove($name)
    {
        //remove one variable from $_SESSION
        unset($_SESSION[$name]);
    }
    function removeAll()
    {
        //remove all variables from $_SESSION
        session_unset();
    }
    function writeDb()
    {
    }
}
