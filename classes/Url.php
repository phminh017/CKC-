<?php
class Url
{
    public $enablePrettyUrl = false;
    public $showScriptFile = true;
    public $rules = [];
    public $suffix = ".html";

    function __construct($urlConfig = [])
    {
        if (array_key_exists('enablePrettyUrl', $urlConfig)) {
            $this->enablePrettyUrl = $urlConfig['enablePrettyUrl'];
        }
        if (array_key_exists('showScriptFile', $urlConfig)) {
            $this->showScriptFile = $urlConfig['showScriptFile'];
        }
        if (array_key_exists('rules', $urlConfig)) {
            $this->rules = $urlConfig['rules'];
        }
        if (array_key_exists('suffix', $urlConfig)) {
            $this->ext = $urlConfig['suffix'];
        }
    }

    function setHostname()
    {
    }

    function getLocation()
    {
    }
}
