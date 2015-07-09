<?php

require_once __DIR__ . '/TextSpinner/SpinnerChief.php';

require_once SYNDICATE_POST_PLUGIN_DIR . 'PublishDriverManager.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'View.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'MailNotificator.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'MailNotificatorException.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'DisposableAdminMessage.php';

/**
 * Description of post-syndicator
 *
 * @author dkociuba
 */
class PostSyndicator {

    /**
     * @var WP_Post
     */
    private $post;

    /**
     *
     * @var SpinnerChief
     */
    private $spinnerChief;

    /**
     * @var PublishDriverManager
     */
    private $publishDriverManager;

    public function __construct(WP_Post $post) {
        $this->post = $post;
        $this->spinnerChief = new SpinnerChief();
        $this->spinnerChief->loadPreservedParameters();

        $this->publishDriverManager = new PublishDriverManager();
    }

    public function syndicate() {
        $view = new View('publishPostEmail.php');
        $view->setParameter('postTitle', $this->post->post_title);
        $this->spinnerChief->setTextToSpin($this->post->post_content);
        
        $mailNotificator = new MailNotificator();
        $mailNotificator->loadPreservedParameters();
        
        $drivers = $this->publishDriverManager->getRegistredDrivers();
        foreach ($drivers as $driver) {
            $driver->loadPreservedParameters();
            if (!$driver->getIsActive()) {
                continue;
            }
            $driver->publish($this->post->post_title, $this->spinnerChief->getNextVariant());
            //$driver->publish($this->post->post_title, $this->post->post_content);
            $link = $driver->getPublishedPostURL();
            add_post_meta($this->post->ID, 'external_url', $link);

            $view->setParameter('postUrl', $link);
            $view->setParameter('driverName', $driver->getName());
            try {
                $mailNotificator->sendMail('Post has been published on '.$driver->getName(), $view->render(true));
            } catch(MailNotificatorException $ex) {
                DisposableAdminMessage::getInstance()->pushMessage($ex->getMessage());
            }
        }
    }

}
