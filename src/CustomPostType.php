<?php

namespace RemoteSnippets;

class CustomPostType {
    public static function addPostType()
    {
        register_post_type('remotesnippets', [
            'label' => 'RemoteSnippets',
            'labels' => [
                'name' => 'RemoteSnippets',
                'singular_name' => 'RemoteSnippet',
                'menu_name' => 'RemoteSnippets',
                'name_admin_bar' => 'RemoteSnippets',
                'all_items' => 'RemoteSnippets',
                'add_new' => 'Add New RemoteSnippet',
                'add_new_item' => 'Add New RemoteSnippet',
                'edit_item' => 'Edit RemoteSnippet',
                'new_item' => 'New RemoteSnippet',
                'view_item' => 'View Final RemoteSnippet',
                'search_items' =>'Search RemoteSnippets',
                'not_found' => 'No RemoteSnippets found.',
                'not_found_in_trash' => 'No RemoteSnippets found in trash',
                'parent_item_colon' => 'Parent RemoteSnippets found in trash',

            ],
            'description' => 'Code templates for the Remote Snippets plugin.',
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'revisions'),
            'has_archive' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-layout',
        ]);

        add_filter('user_can_richedit', function($default) {
            global $post;
            if (get_post_type($post) == 'remotesnippets') {
                return false;
            }
            return $default;
        }, 10, 1);

        add_filter('manage_remotesnippets_posts_columns', [self::class, 'set_custom_edit_remotesnippets_columns']);
        add_action('manage_remotesnippets_posts_custom_column' , [self::class, 'custom_remotesnippets_column'], 10, 2);

        add_action('admin_menu', [self::class, 'admin_menu_error_bubbles']);
    }

    private static function getErrorAmount()
    {
        $transKey = 'remotesnippet_errors';
        if (false === ($errorCount = get_transient($transKey))) {
            $args = [
                'post_type'=> 'remotesnippets',
                'post_status' => 'publish',
                'meta_key'  => '_remotesnippets_error_status',
                'meta_value'   => 'Error',
                'meta_compare' => '='
            ];
            $errorCount = count(get_posts($args));
            set_transient($transKey, $errorCount, 60);
        }
        return $errorCount;
    }

    public static function admin_menu_error_bubbles()
    {
        global $menu;

        $error_count = self::getErrorAmount();
        if ($error_count > 0) {
            foreach ($menu as $key => $item) {
                if ($item[2] == 'edit.php?post_type=remotesnippets') {
                    $menu[$key][0] .= ' <span class="update-plugins count-' . $error_count . '"><span class="plugin-count">' . $error_count . '</span></span>';
                }
            }
        }
    }

    public static function set_custom_edit_remotesnippets_columns($columns) {
        $columns['status'] = 'Status';
        $columns['ds'] = 'Data Source Type';
        return $columns;
    }

    public static function custom_remotesnippets_column( $column, $post_id ) {
        switch ($column) {
            case 'status' :
                $status = get_post_meta($post_id, '_remotesnippets_error', true);
                if (isset($status['status'])) {
                    echo $status['status'];
                } else {
                    echo 'Unknown';
                }
                if (isset($status['ts'])) {
                    echo "<br>Since ".human_time_diff($status['ts'], time()). " ago";
                }
                break;
            case 'ds' :
                $settings = get_post_meta($post_id, '_remotesnippets', true);
                echo PostMetaData::$ds_options[$settings['remotesnippet_ds']];
                break;
        }
    }


}
