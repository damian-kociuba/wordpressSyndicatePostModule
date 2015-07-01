<?php

/**
 * Description of View
 *
 * @author dkociuba
 */
class View {

    private static $globalViewDirectory = '';
    private static $globalScriptDirectory = '';
    private static $globalStyleDirectory = '';

    public static function setGlobalViewDirectory($path) {
        self::$globalViewDirectory = $path;
    }

    public static function setGlobalScriptDirectory($path) {
        self::$globalScriptDirectory = $path;
    }

    public static function setGlobalStyleDirectory($path) {
        self::$globalStyleDirectory = $path;
    }

    private $viewName;
    private $parameters = array();
    private $wordpressScripts = array();
    private $scripts = array();
    private $styles = array();

    public function __construct($viewName) {
        $this->viewName = $viewName;
    }

    public function setParameter($name, $value) {
        $this->parameters[$name] = $value;
    }

    public function addWordpressBuildInScript($scriptName) {
        $this->wordpressScripts[] = $scriptName;
    }
    public function addScript($filePath) {
        $this->scripts[] = $filePath;
    }

    public function addStyle($styleFile) {
        $this->styles[] = $styleFile;
    }

    public function render() {
        foreach ($this->styles as $styleFile) {
            wp_register_style(basename($styleFile), self::$globalStyleDirectory . $styleFile);
            wp_enqueue_style(basename($styleFile));
        }
        foreach ($this->wordpressScripts as $scriptName) {
            wp_enqueue_script(basename($scriptName));
        }
        foreach ($this->scripts as $scriptPath) {
            wp_register_script(basename($scriptPath), self::$globalScriptDirectory . $scriptPath);
            wp_enqueue_script(basename($scriptPath));
        }

        extract($this->parameters);
        require(self::$globalViewDirectory . $this->viewName);
    }

}
