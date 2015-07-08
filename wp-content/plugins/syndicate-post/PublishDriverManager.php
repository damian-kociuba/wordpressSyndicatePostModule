<?php

require_once 'PublishDriver/Tumblr/TumblrDriver.php';
require_once 'PublishDriver/Blogger/BloggerDriver.php';
require_once 'PublishDriver/PublishDriver.php';

/**
 * Description of DriverManager
 *
 * @author dkociuba
 */
class PublishDriverManager {

    /**
     *
     * @var PublishDriver[]
     */
    private $registredDrivers = array();

    public function __construct() {
        $this->registredDrivers = array(
            new TumblrDriver(),
            new BloggerDriver()
        );
    }

    /**
     * @return PublishDriver[]
     */
    public function getRegistredDrivers() {
        return $this->registredDrivers;
    }

    /**
     * 
     * @param string $name
     * @return PublishDriver
     */
    public function getDriverByName($name) {
        foreach ($this->registredDrivers as $driver) {
            if (strcasecmp($name, $driver->getName()) === 0) {
                return $driver;
            }
        }
        return null;
    }

}
