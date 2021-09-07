<?php
class Style
{
    public $mainCSS = 'main.css';
    public $cssFiles = [];

    public function __construct($styleConfig = [])
    {
        if (array_key_exists('main', $styleConfig)) {
            $this->mainCSS = $styleConfig['main'];
        }
    }
    public function loadMainCss()
    {
        $href = CSS . DS . $this->mainCSS;
        echo "<link rel='stylesheet' type='text/css' href='$href'>";
    }
    function registerCssFiles($cssFiles = [])
    {
        //$key=>$value;
        foreach ($cssFiles as $name => $cssFile) {
            $this->cssFiles[$name] = $cssFile;
        }
    }
    function loadCss($name = '')
    {
        if (array_key_exists($name,  $this->cssFiles)) {
            $href = CSS . DS . $this->cssFiles[$name];
            echo "<link rel='stylesheet' type='text/css' href='$href'>";
        }
    }
}
