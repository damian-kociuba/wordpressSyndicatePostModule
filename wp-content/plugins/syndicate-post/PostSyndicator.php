<?php

require_once __DIR__ . '/TextSpinner/SpinnerChief.php';

require_once SYNDICATE_POST_PLUGIN_DIR . 'PublishDriverManager.php';

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

        $this->spinnerChief->setTextToSpin($this->post->post_content);

        $drivers = $this->publishDriverManager->getRegistredDrivers();
        foreach ($drivers as $driver) {
            $driver->loadPreservedParameters();
            if (!$driver->getIsActive()) {
                continue;
            }
            $driver->publish($this->post->post_title, $this->spinnerChief->getNextVariant());
        }
    }

}
