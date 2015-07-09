<?php

require_once SYNDICATE_POST_PLUGIN_DIR . 'SyndicatePostException.php';

/**
 * Description of MailNotificatorException
 *
 * @author dkociuba
 */
class MailNotificatorException extends Exception {

    private $phpMailer;

    function getPhpMailer() {
        return $this->phpMailer;
    }

    function setPhpMailer($phpMailer) {
        $this->phpMailer = $phpMailer;
    }

}
