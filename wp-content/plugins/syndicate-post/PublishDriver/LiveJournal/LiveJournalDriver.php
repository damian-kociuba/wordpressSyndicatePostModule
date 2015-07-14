<?php

require_once realpath(dirname(__FILE__) . '/../PublishDriver.php');
require_once SYNDICATE_POST_PLUGIN_DIR . 'Preverseable.php';

/**
 * Description of LiveJournalClient
 *
 * @author dkociuba
 */
class LiveJournalDriver implements PublishDriver, Preverseable {

    private $login;
    private $password;
    private $isActive;

    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = !empty($isActive);
    }

    public function getName() {
        return 'LiveJournal';
    }

    public function getPublishedPostURL() {
        
    }

    public function getRequiredParameters() {
        return array(
            array(
                'name' => 'login',
                'label' => 'Login',
                'value' => $this->login
            ),
            array(
                'name' => 'password',
                'label' => 'Password',
                'value' => $this->password
            ),
        );
    }

    public function loadPreservedParameters() {
        $parameters = get_option('syndicate_post_driver_liveJournal_parameters');
        if (empty($parameters)) {
            return;
        }
        $this->setRequiredParameters($parameters);

        $this->isActive = !empty($parameters['isActive']);
    }

    public function preserveParameters() {
        $parameters = array(
            'isActive' => $this->isActive,
            'login' => $this->login,
            'password' => $this->password,
        );
        update_option('syndicate_post_driver_liveJournal_parameters', $parameters);
    }

    public function publish($title, $content) {
        $client = new IXR_Client();
    }

    public function setRequiredParameters($parameters) {
        $this->login = isset($parameters['login']) ? $parameters['login'] : null;
        $this->password = isset($parameters['password']) ? $parameters['password'] : null;
    }

    public function testConnection() {
        $request = xmlrpc_encode_request("LJ.XMLRPC.login", array('username' => $this->login, 'password' => $this->password));
        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));
        $file = file_get_contents("http://www.livejournal.com/interface/xmlrpc", false, $context);
        $response = xmlrpc_decode($file);
        if (is_array($response) && xmlrpc_is_fault($response)) {
            //trigger_error("xmlrpc: $response[faultString] ($response[faultCode])");
            return false;
        } else {
            return true;
        }
        exit();
    }

}
