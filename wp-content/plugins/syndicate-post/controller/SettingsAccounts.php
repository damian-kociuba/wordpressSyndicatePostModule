<?php

require_once 'Controller.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'View.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'PublishDriverManager.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'PublishDriver/OAuth2PublishDriver.php';

/**
 * Description of settingsAccounts
 *
 * @author dkociuba
 */
class SettingsAccounts extends Controller {

    /**
     *
     * @var PublishDriverManager
     */
    private $publishDriverManager;

    public function __construct() {
        $this->addRoute('index', 'index', 'GET');
        $this->addRoute('index', 'handleFormWithSettings', 'POST');
        $this->addRoute('handleRedirect', 'handleOAuth2Redirect');
        $this->addRoute('testConnection', 'testConnection', 'POST');

        $this->publishDriverManager = new PublishDriverManager();
    }

    public function actionIndex() {
        $defaultFormValues = array();

        $drivers = $this->publishDriverManager->getRegistredDrivers();
        foreach ($drivers as $driver) {
            $defaultFormValues['driver_active'][$driver->getName()] = get_option('syndicate_post_driver_' . $driver->getName() . '_is_active');
            $driver->loadPreservedParameters();
            if ($driver instanceof OAuth2PublishDriver) {
                $driver->setRedirectURL($this->getLinkToRoute('handleRedirect', false, array('driver' => 'blogger')));
            }
        }

        $view = new View('accounts-page.php');
        $view->addScript('accounts.js');
        $view->addWordpressBuildInScript('jquery');
        $view->addWordpressBuildInScript('jquery-ui-core');
        $view->addWordpressBuildInScript('jquery-ui-tabs');
        $view->addStyle('layout.css');


        $view->setParameter('drivers', $drivers);
        $view->setParameter('testConnectionURL', $this->getLinkToRoute('testConnection', true));
        foreach ($defaultFormValues as $field => $value) {
            $view->setParameter($field, $value);
        }
        $view->render();
    }

    public function actionHandleFormWithSettings() {
        $driverName = filter_input(INPUT_POST, 'driver_name');
        $driverActive = isset($_POST['driver_' . $driverName . '_is_active']);
        $driverParameters = $_POST['driver_' . $driverName . '_parameter'];
        $driver = $this->publishDriverManager->getDriverByName($driverName);
        $driver->setRequiredParameters($driverParameters);
        $driver->preserveParameters();
        update_option('syndicate_post_driver_' . $driverName . '_is_active', $driverActive);

        $this->actionIndex();
    }

    public function actionHandleOAuth2Redirect() {
        $view = new View('account-page-oauth-redirect.php');
        try {
            $driverName = $_GET['driver'];
            $driver = $this->publishDriverManager->getDriverByName($driverName);

            if ($driver instanceof OAuth2PublishDriver) {
                $driver->setRedirectURL($this->getLinkToRoute('handleRedirect', false, array('driver' => 'blogger')));
                $driver->readAuthenticationCode();
                $view->setParameter('comeBackURL', $this->getLinkToRoute('index'));
                $view->render();
            } else {
                throw new Exception('Driver not implement OAuth2PublishDriver interface');
            }
        } catch (Exception $e) {
            $view->setParameter('errorMessage', $e->getMessage());
            $view->render();
        }
    }

    public function actionTestConnection() {
        try {
            $driverName = filter_input(INPUT_POST, 'driver_name');
            $parameters = json_decode(filter_input(INPUT_POST, 'driver_parameters'), true);
            $driverManager = new PublishDriverManager();
            $driver = $driverManager->getDriverByName($driverName);
            $driver->setRequiredParameters($parameters);


            echo json_encode($driver->testConnection());
        } catch (Exception $e) {
            echo json_encode(false);
            throw $e;
        }
        exit();
    }

}
