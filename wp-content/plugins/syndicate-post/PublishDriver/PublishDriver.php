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
    
    public function preserveParameters();

    public function loadPreservedParameters();
}
