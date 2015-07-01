<?php

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

    public function __construct(WP_Post $post) {
        $this->post = $post;
    }
    
    
}
