<?php

/**
 * Description of DisposableAdminMessage
 *
 * @author dkociuba
 */
class DisposableAdminMessage {

    private static $instance = null;

    /**
     * 
     * @return DisposableAdminMessage
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DisposableAdminMessage();
        }
        return self::$instance;
    }

    private $messages = array();

    private function __construct() {
        if (!session_id()) {
            throw new Exception('Class ' . __CLASS__ . 'needs session start');
        }
        if (empty($_SESSION['DisposableAdminMessages'])) {
            $this->messages = array();
        } else {
            $this->messages = json_decode($_SESSION['DisposableAdminMessages']);
        }
    }

    public function __destruct() {
        $_SESSION['DisposableAdminMessages'] = json_encode($this->messages);
    }

    public function pushMessage($message) {
        array_push($this->messages, $message);
    }

    public function popMessage() {
        return array_pop($this->messages);
    }

}
