<?php

namespace RemoteSnippets;

class SingleTemplate
{
    public static function remotesnippet_single_template($path)
    {
        global $post;

        if (get_post_type($post) == 'remotesnippets') {
            return __DIR__ . '/templates/remotesnippets_single.php';
        }
        else {
            return $path;
        }
    }

    public static function remotesnippet_the_content($content)
    {
        if (is_singular('remotesnippets')) {
            return $content;
        }
        return $content;
    }
}
