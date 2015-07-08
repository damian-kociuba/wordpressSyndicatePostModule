<?php

require_once realpath(dirname(__FILE__) . '/../PublishDriver.php');
require_once 'client/vendor/autoload.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'Preverseable.php';

/**
 * Description of ThumblrDriver
 *
 * @author dkociuba
 */
class TumblrDriver implements PublishDriver, Preverseable {

    private $customerKey;
    private $secretKey;
    private $token;
    private $secret;
    private $isActive;
    private $blogName;

    public function getName() {
        return 'Tumblr';
    }

    function getIsActive() {
        return $this->isActive;
    }

    function setIsActive($isActive) {
        $this->isActive = $isActive;
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
            array(
                'name' => 'blogName',
                'label' => 'Blog name',
                'value' => $this->blogName
            ),
        );
    }

    public function preserveParameters() {
        $parameters = array(
            'isActive' => $this->isActive,
            'customer_key' => $this->customerKey,
            'secret_key' => $this->secretKey,
            'token' => $this->token,
            'secret' => $this->secret,
            'blogName' => $this->blogName
        );
        update_option('syndicate_post_driver_tumblr_parameters', $parameters);
    }

    public function loadPreservedParameters() {
        $parameters = get_option('syndicate_post_driver_tumblr_parameters');
        $this->isActive = !empty($parameters['isActive']);
        $this->setRequiredParameters($parameters);
    }

    public function publish($title, $content) {
        $client = $this->getClient();
        $postData = array('title' => $title, 'body' => $content);
        $client->createPost($this->blogName, $postData);
    }

    public function setRequiredParameters($parameters) {
        $this->customerKey = isset($parameters['customer_key']) ? $parameters['customer_key'] : null;
        $this->secretKey = isset($parameters['secret_key']) ? $parameters['secret_key'] : null;
        $this->token = isset($parameters['token']) ? $parameters['token'] : null;
        $this->secret = isset($parameters['secret']) ? $parameters['secret'] : null;
        $this->blogName = isset($parameters['blogName']) ? $parameters['blogName'] : null;
    }

    public function testConnection() {
        $client = $this->getClient();

        $response = $client->getUserInfo();
        if (isset($response->user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @return \Tumblr\API\Client
     */
    private function getClient() {
        return new Tumblr\API\Client(
                $this->customerKey, $this->secretKey, $this->token, $this->secret
        );
    }

//put your code here
}
