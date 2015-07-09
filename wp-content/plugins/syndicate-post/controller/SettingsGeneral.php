<?php

require_once 'Controller.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'View.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'PublishDriverManager.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'TextSpinner/SpinnerChief.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'MailNotificator.php';

/**
 * Description of settingsAccounts
 *
 * @author dkociuba
 */
class SettingsGeneral extends Controller {

    public function __construct() {
        $this->addRoute('index', 'index', 'GET');
        $this->addRoute('save_general_settings', 'handleFormWithGeneralSettings', 'POST');
        $this->addRoute('save_spinner_chief_settings', 'handleFormWithSpinnerChiefSettings', 'POST');
        $this->addRoute('save_notification_settings', 'handleFormWithNotificationSettings', 'POST');
        $this->addRoute('send_test_mail', 'sendTestMail', 'POST');
        $this->addRoute('spinner_chief_test', 'spinnerChiefConnectionTest', 'POST');
    }

    public function actionIndex() {
        $message = '';
        $defaultFormValues = array();

        $spinnerChief = new SpinnerChief();
        $spinnerChief->loadPreservedParameters();
        $mailNotificator = new MailNotificator();
        $mailNotificator->loadPreservedParameters();
        
        $defaultFormValues['minimal_syndicated_content_length'] = get_option('syndicate_post_minimal_syndicated_content_length');
        $defaultFormValues['maximal_syndicated_content_length'] = get_option('syndicate_post_maximal_syndicated_content_length');
        $defaultFormValues['default_anchor_text'] = get_option('syndicate_post_default_anchor_text');
        $defaultFormValues['spinner_chief_url'] = $spinnerChief->getAddress();
        $defaultFormValues['spinner_chief_username'] = $spinnerChief->getUsername();
        $defaultFormValues['spinner_chief_password'] = $spinnerChief->getPassword();
        $defaultFormValues['spinner_chief_api_key'] = $spinnerChief->getApikey();
        $defaultFormValues['phpmailer_host'] = $mailNotificator->getPhpmailerHost();
        $defaultFormValues['phpmailer_username'] = $mailNotificator->getPhpmailerUsername();
        $defaultFormValues['phpmailer_password'] = $mailNotificator->getPhpmailerPassword();
        $defaultFormValues['phpmailer_port'] = $mailNotificator->getPhpmailerPort();
        $defaultFormValues['phpmailer_smtp_secure'] = $mailNotificator->getPhpmailerSmtpSecure();
        $defaultFormValues['phpmailer_to'] = $mailNotificator->getReceiverAddress();

        $view = new View('general-settings-page.php');
        $view->addScript('general-settings.js');
        $view->addWordpressBuildInScript('jquery');
        $view->addWordpressBuildInScript('jquery-ui-core');
        $view->addWordpressBuildInScript('jquery-ui-tabs');
        $view->addStyle('layout.css');
        foreach ($defaultFormValues as $field => $value) {
            $view->setParameter($field, $value);
        }
        $view->setParameter('general_settings_form_action', $this->getLinkToRoute('save_general_settings', true));
        $view->setParameter('spinner_chief_settings_form_action', $this->getLinkToRoute('save_spinner_chief_settings', true));
        $view->setParameter('notification_settings_form_action', $this->getLinkToRoute('save_notification_settings', true));
        $view->setParameter('send_test_mail_ajax_url', $this->getLinkToRoute('send_test_mail', true));
        $view->setParameter('spinner_chief_test_ajax_url', $this->getLinkToRoute('spinner_chief_test', true));
        $view->setParameter('message', $message);
        $view->render();
    }

    public function actionHandleFormWithGeneralSettings() {
        $minimalSyndicatedContentLength = filter_input(INPUT_POST, 'minimal_syndicated_content_length', FILTER_VALIDATE_INT);
        $maximalSyndicatedContentLength = filter_input(INPUT_POST, 'maximal_syndicated_content_length', FILTER_VALIDATE_INT);
        $defaultAnchorText = filter_input(INPUT_POST, 'default_anchor_text');
        $isCorrect = true;
        if ($minimalSyndicatedContentLength >= $maximalSyndicatedContentLength) {
            $message = 'Minimal content length should be less than maximal content length';
            $isCorrect = false;
        }

        if ($isCorrect) {
            update_option('syndicate_post_minimal_syndicated_content_length', $minimalSyndicatedContentLength);
            update_option('syndicate_post_maximal_syndicated_content_length', $maximalSyndicatedContentLength);
            update_option('syndicate_post_default_anchor_text', $defaultAnchorText);
            $message = 'Saved settings';
        }
        header('Location: ' . $this->getLinkToRoute('index'));
    }

    public function actionHandleFormWithSpinnerChiefSettings() {
        $url = filter_input(INPUT_POST, 'spinner_chief_url');
        $username = filter_input(INPUT_POST, 'spinner_chief_username');
        $password = filter_input(INPUT_POST, 'spinner_chief_password');
        $apiKey = filter_input(INPUT_POST, 'spinner_chief_api_key');

        $spinnerChief = new SpinnerChief();
        $spinnerChief->loadPreservedParameters();
        $spinnerChief->setAddress($url);
        $spinnerChief->setUsername($username);
        $spinnerChief->setPassword($password);
        $spinnerChief->setApikey($apiKey);
        $spinnerChief->preserveParameters();

        header('Location: ' . $this->getLinkToRoute('index'));
    }

    public function actionHandleFormWithNotificationSettings() {
        $host = filter_input(INPUT_POST, 'phpmailer_host');
        $username = filter_input(INPUT_POST, 'phpmailer_username');
        $password = filter_input(INPUT_POST, 'phpmailer_password');
        $port = filter_input(INPUT_POST, 'phpmailer_port');
        $smtpSecure = filter_input(INPUT_POST, 'phpmailer_smtp_secure');
        $receiverAddress = filter_input(INPUT_POST, 'phpmailer_to');

        $mailNotificator = new MailNotificator();
        $mailNotificator->setPhpmailerHost($host);
        $mailNotificator->setPhpmailerUsername($username);
        $mailNotificator->setPhpmailerPassword($password);
        $mailNotificator->setPhpmailerPort($port);
        $mailNotificator->setPhpmailerSmtpSecure($smtpSecure);
        $mailNotificator->setReceiverAddress($receiverAddress);
        $mailNotificator->preserveParameters();

        header('Location: ' . $this->getLinkToRoute('index'));
    }

    public function actionSendTestMail() {
        $host = filter_input(INPUT_POST, 'host');
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $port = filter_input(INPUT_POST, 'port');
        $to = filter_input(INPUT_POST, 'to');
        $smtpSecure = filter_input(INPUT_POST, 'smtp_secure');

        $mailNotificator = new MailNotificator();
        $mailNotificator->setPhpmailerHost($host);
        $mailNotificator->setPhpmailerUsername($username);
        $mailNotificator->setPhpmailerPassword($password);
        $mailNotificator->setPhpmailerPort($port);
        $mailNotificator->setPhpmailerSmtpSecure($smtpSecure);
        $mailNotificator->setReceiverAddress($to);

        echo json_encode($mailNotificator->sendTestEmail());
        exit;
    }

    public function actionSpinnerChiefConnectionTest() {
        $url = filter_input(INPUT_POST, 'url');
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $apiKey = filter_input(INPUT_POST, 'api_key');

        $spinnerChief = new SpinnerChief();
        $spinnerChief->setAddress($url);
        $spinnerChief->setUsername($username);
        $spinnerChief->setPassword($password);
        $spinnerChief->setApikey($apiKey);

        try {
            if ($spinnerChief->testConnection()) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        } catch (Exception $e) {
            echo json_encode(false);
        }
        exit;
    }

}
