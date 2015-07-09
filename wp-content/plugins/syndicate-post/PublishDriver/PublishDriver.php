<?php

/**
 *
 * @author dkociuba
 */
interface PublishDriver {

    public function getRequiredParameters();

    public function setRequiredParameters($parameters);

    public function testConnection();

    public function publish($title, $content);

    public function getName();
    
    public function getIsActive();
    
    public function setIsActive($isActive);
    
    public function getPublishedPostURL();
}
