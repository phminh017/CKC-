<?php
class Request
{
  
    function getHostname()
    {
        // return $this->hostname;
    }

    ///Client 
    function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }
    function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }
    function getRequestTime()
    {
        return $_SERVER['REQUEST_TIME'];
    }
    function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
    function getQueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }
    function getRemoteAddr()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    function post($name)
    {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }
}
