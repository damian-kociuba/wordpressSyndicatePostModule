<?php

require_once __DIR__ . '/TextSpinner/SpinnerChief.php';
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

    public function __construct(WP_Post $post) {
        $this->post = $post;
        $this->spinnerChief = new SpinnerChief();
    }
    
    public function syndicate() {
        
    }
    
}
