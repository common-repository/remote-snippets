<?php

namespace RemoteSnippets;

class RemoteSnippets {

    public function getNamespace()
    {
        $components = explode('\\', get_class($this));
        $z = array_pop($components);
        return implode('\\', $components);
    }

    public function setup()
    {
        $ns = $this->getNamespace();
        add_shortcode('remotesnippet', [$ns . '\ShortCode', 'handleShortcode']);

        $cptClass = $ns . '\CustomPostType';
        $cptClass::addPostType();

        add_action('add_meta_boxes',  [$ns . '\PostMetaData', 'addMetaBoxes']);
        add_action('pre_post_update', [$ns . '\PostMetaData', 'remotesnippet_pre_post_update']);

        add_filter('single_template', [$ns . '\SingleTemplate', 'remotesnippet_single_template']);
        add_filter('the_content',     [$ns . '\SingleTemplate', 'remotesnippet_the_content'], 1);
    }
}
