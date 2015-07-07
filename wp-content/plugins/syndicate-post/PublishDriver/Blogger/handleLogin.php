<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'BloggerDriver.php';

$bloggerDriver = new BloggerDriver();
$bloggerDriver->receiveCodeHandler();
 header("Location: SyndicateWordpressModule/wp-content/plugins/syndicate-post/PublishDriver/Blogger/handleLogin.php");