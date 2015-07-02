<?php

require_once realpath(dirname(__FILE__) . '/../PublishDriver.php');

/**
 *
 * @author dkociuba
 */
class BloggerDriver implements PublishDriver {

    private $customerKey;
    private $secretKey;
    private $token;
    private $secret;
    
    public function getName() {
        return 'Blogger';
    }

    public function getRequiredParameters() {
        return array(
            array(
                'name' => 'customer_key',
                'label' => 'Customer key'
            ),
            array(
                'name' => 'secret_key',
                'label' => 'Secret key'
            ),
            
        );
    }

    public function publish($title, $content) {
        
    }

    public function setRequiredParameters($parameters) {
        $this->customerKey = $parameters['customer_key'];
        $this->secretKey = $parameters['secret_key'];
        $this->token = $parameters['token'];
        $this->secret = $parameters['secret'];
    }

    public function testConnection() {
       return true;
    }

//put your code here
}
