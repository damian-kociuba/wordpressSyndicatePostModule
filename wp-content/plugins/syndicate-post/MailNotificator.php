<?php

require_once SYNDICATE_POST_PLUGIN_DIR . 'Preverseable.php';
require_once realpath(SYNDICATE_POST_PLUGIN_DIR.'../../../wp-includes/class-phpmailer.php');
require_once SYNDICATE_POST_PLUGIN_DIR . 'MailNotificatorException.php';
/**
 * Description of MailNotification
 *
 * @author dkociuba
 */
class MailNotificator implements Preverseable {

    private $receiverAddress;

    /**
     *
     * @var phpMailer
     */
    private $phpMailer;

    public function __construct() {
        $this->phpMailer = new PHPMailer();
        $this->phpMailer->isSMTP();
        $this->phpMailer->SMTPAuth = true;
    }

    function getPhpmailerHost() {
        return $this->phpMailer->Host;
    }

    function getPhpmailerUsername() {
        return $this->phpMailer->Username;
    }

    function getPhpmailerPassword() {
        return $this->phpMailer->Password;
    }

    function getPhpmailerPort() {
        return $this->phpMailer->Port;
    }

    function getPhpmailerSmtpSecure() {
        return $this->phpMailer->SMTPSecure;
    }

    function getReceiverAddress() {
        return $this->receiverAddress;
    }

    function setPhpmailerHost($phpmailerHost) {
        $this->phpMailer->Host = $phpmailerHost;
    }

    function setPhpmailerUsername($phpmailerUsername) {
        $this->phpMailer->Username = $phpmailerUsername;
    }

    function setPhpmailerPassword($phpmailerPassword) {
        $this->phpMailer->Password = $phpmailerPassword;
    }

    function setPhpmailerPort($phpmailerPort) {
        $this->phpMailer->Port = $phpmailerPort;
    }

    /**
     * @param string $phpmailerSmtpSecure (empty string) | tls | ssl
     */
    function setPhpmailerSmtpSecure($phpmailerSmtpSecure) {
        $this->phpMailer->SMTPSecure = $phpmailerSmtpSecure;
    }

    function setReceiverAddress($receiverAddress) {
        $this->receiverAddress = $receiverAddress;
    }

    public function sendTestEmail() {
        $this->phpMailer->From = $this->getPhpmailerUsername();
        $this->phpMailer->FromName = $this->getPhpmailerUsername();
        $this->phpMailer->addReplyTo($this->getPhpmailerUsername(), $this->getPhpmailerUsername());
        $this->phpMailer->addAddress($this->receiverAddress);
        $this->phpMailer->Subject = 'Test mail';
        $this->phpMailer->msgHTML('Test message');
        return $this->phpMailer->send();
    }

    public function loadPreservedParameters() {
        $params = get_option('syndicate_post_mail_notification');
        $this->setPhpmailerHost($params['host']);
        $this->setPhpmailerUsername($params['username']);
        $this->setPhpmailerPassword($params['password']);
        $this->setPhpmailerPort($params['port']);
        $this->setPhpmailerSmtpSecure($params['smtpSecure']);
        $this->setReceiverAddress($params['receiver']);
    }

    public function preserveParameters() {
        $params = array(
            'host' => $this->getPhpmailerHost(),
            'username' => $this->getPhpmailerUsername(),
            'password' => $this->getPhpmailerPassword(),
            'port' => $this->getPhpmailerPort(),
            'smtpSecure' => $this->getPhpmailerSmtpSecure(),
            'receiver' => $this->getReceiverAddress()
        );

        update_option('syndicate_post_mail_notification', $params);
    }

    public function sendMail($title, $content) {
        $this->phpMailer->From = $this->getPhpmailerUsername();
        $this->phpMailer->FromName = 'Syndicate Post Module';
        $this->phpMailer->Subject = $title;
        $this->phpMailer->msgHTML($content);
        $this->phpMailer->addAddress($this->getReceiverAddress());
        $status =  $this->phpMailer->send();
        if(!$status) {
            $ex = new MailNotificatorException('Cannot send mail "'.$title.'" to '.$this->getReceiverAddress());
            $ex->setPhpMailer($this->phpMailer);
            throw $ex;
        }
    }
}
