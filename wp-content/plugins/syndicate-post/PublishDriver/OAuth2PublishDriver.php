<?php
require_once 'PublishDriver.php';
/**
 * Description of OAuth2PublishDriver
 *
 * @author dkociuba
 */
interface OAuth2PublishDriver extends PublishDriver {
    public function getLoginUrl();
    public function readAuthenticationCode();
    function getRedirectURL();

    function setRedirectURL($redirectURL);
}
