<?php

require_once realpath(dirname(__FILE__) . '/../PublishDriver.php');
require_once 'client/vendor/autoload.php';

/**
 * Description of ThumblrDriver
 *
 * @author dkociuba
 */
class TumblrDriver implements PublishDriver {

    private $customerKey;
    private $secretKey;
    private $token;
    private $secret;

    public function getName() {
        return 'Tumblr';
    }

    public function getRequiredParameters() {
        return array(
            array(
                'name' => 'customer_key',
                'label' => 'Customer key',
                'value' => $this->customerKey
            ),
            array(
                'name' => 'secret_key',
                'label' => 'Secret key',
                'value' => $this->secretKey
            ),
            array(
                'name' => 'token',
                'label' => 'Token',
                'value' => $this->token
            ),
            array(
                'name' => 'secret',
                'label' => 'Secret',
                'value' => $this->secret
            ),
        );
    }

    public function preserveParameters() {
        $parameters = array(
            'customer_key' => $this->customerKey,
            'secret_key' => $this->secretKey,
            'token' => $this->token,
            'secret' => $this->secret
        );
        update_option('syndicate_post_driver_tumblr_parameters', $parameters);
    }

    public function loadPreservedParameters() {
        $parameters = get_option('syndicate_post_driver_tumblr_parameters');
        $this->setRequiredParameters($parameters);
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
        $client = new Tumblr\API\Client(
                $this->customerKey, $this->secretKey, $this->token, $this->secret
        );

        $response = $client->getUserInfo();
        if (isset($response->user)) {
            return true;
        } else {
            return false;
        }
    }

//put your code here
}
