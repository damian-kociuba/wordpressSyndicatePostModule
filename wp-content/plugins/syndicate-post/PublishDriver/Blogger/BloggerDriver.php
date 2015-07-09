<?php

require_once realpath(dirname(__FILE__) . '/../OAuth2PublishDriver.php');
require_once 'client/google-api-php-client/src/Google/autoload.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'Preverseable.php';

/**
 *
 * @author dkociuba
 */
class BloggerDriver implements OAuth2PublishDriver, Preverseable {

    private $clientId;
    private $clientSecret;
    private $developerKey;
    private $redirectURL;
    private $accessToken;
    private $isActive;
    private $blogId;
    private $lastPublishedPostURL;

    /**
     * authentication code receiving from blogger
     */
    private $code;

    public function getName() {
        return 'Blogger';
    }

    function getIsActive() {
        return $this->isActive;
    }

    function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    function getRedirectURL() {
        return $this->redirectURL;
    }

    function setRedirectURL($redirectURL) {
        $this->redirectURL = $redirectURL;
    }

    public function getRequiredParameters() {
        return array(
            array(
                'name' => 'clientId',
                'label' => 'Client Id',
                'value' => $this->clientId
            ),
            array(
                'name' => 'clientSecret',
                'label' => 'Client Secret',
                'value' => $this->clientSecret
            ),
            array(
                'name' => 'developerKey',
                'label' => 'Developer key',
                'value' => $this->developerKey
            ),
            array(
                'name' => 'blogId',
                'label' => 'Blog id',
                'value' => $this->blogId
            ),
        );
    }

    public function publish($title, $content) {
        $client = $this->getClient();
        $blogger = new Google_Service_Blogger($client);

        $mypost = new Google_Service_Blogger_Post();
        $mypost->setTitle($title);
        $mypost->setContent($content);

        $data = $blogger->posts->insert($this->blogId, $mypost); //post id needs here - put your blogger blog id
        $this->lastPublishedPostURL = $data->url;
    }

    public function getPublishedPostURL() {
        return $this->lastPublishedPostURL;
    }

    public function preserveParameters() {
        $parameters = array(
            'isActive' => $this->isActive,
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'developerKey' => $this->developerKey,
            'code' => $this->code,
            'accessToken' => $this->accessToken,
            'blogId' => $this->blogId
        );
        update_option('syndicate_post_driver_blogger_parameters', $parameters);
    }

    public function loadPreservedParameters() {
        $parameters = get_option('syndicate_post_driver_blogger_parameters');
        if (empty($parameters)) {
            return;
        }
        $this->setRequiredParameters($parameters);

        if (!empty($parameters['code'])) {
            $this->code = $parameters['code'];
        }
        if (!empty($parameters['accessToken'])) {
            $this->accessToken = $parameters['accessToken'];
        }
        $this->isActive = !empty($parameters['isActive']);
    }

    public function setRequiredParameters($parameters) {
        $this->clientId = isset($parameters['clientId']) ? $parameters['clientId'] : null;
        $this->clientSecret = isset($parameters['clientSecret']) ? $parameters['clientSecret'] : null;
        $this->developerKey = isset($parameters['developerKey']) ? $parameters['developerKey'] : null;
        $this->blogId = isset($parameters['blogId']) ? $parameters['blogId'] : null;
    }

    public function testConnection() {
        
    }

    public function getLoginUrl() {
        $client = $this->getClient();
        return $client->createAuthUrl();
    }

    public function readAuthenticationCode() {
        if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
            $this->loadPreservedParameters();
            $client = $this->getClient();
            $this->code = $_GET['code'];
            $client->authenticate($this->code);
            $this->accessToken = $client->getAccessToken();
            $this->preserveParameters();
        }
    }

    private function getClient() {
        $client = new Google_Client();
        $client->setAccessType('online');
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->setDeveloperKey($this->developerKey);
        $client->setScopes(array('https://www.googleapis.com/auth/blogger'));
        $client->setRedirectUri($this->redirectURL);
        if (!empty($this->accessToken)) {
            $client->setAccessToken($this->accessToken);
        }
        return $client;
    }

}
