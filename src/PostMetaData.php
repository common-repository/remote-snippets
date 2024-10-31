<?php

namespace RemoteSnippets;

class PostMetaData {

    public static $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';

    private static $settings = null;

    public static $ds_options = [
        'none' => '(No External Data Source)',
        'url_json' => 'URL returning JSON',
        'url_csv' => 'URL returning CSV',
        'url_xml' => 'URL returning XML',
    ];

    public static $error_options = [
        'nothing' => 'No Output',
        'error' => 'Display the Error',
    ];

    protected static function getSettings($post_id) {
        if (empty(self::$settings)) {
            self::$settings = get_post_meta($post_id, '_remotesnippets', true);
        }
        return self::$settings;
    }

    public static function addMetaBoxes()
    {
        add_meta_box(
            'remotesnippet_shortcode',
            'Shortcode',
            [get_called_class(), 'remotesnippet_shortcode'],
            'remotesnippets',
            'normal',
            'high'
        );
        add_meta_box(
            'remotesnippet_ds_settings',
            'External Data Source',
            [get_called_class(), 'remotesnippet_ds_settings_html'],
            'remotesnippets',
            'normal',
            'high'
        );
        add_meta_box(
            'remotesnippet_cache_settings',
            'Result Cache',
            [get_called_class(), 'remotesnippet_cache_settings_html'],
            'remotesnippets'
        );
        add_meta_box(
            'remotesnippet_error_settings',
            'Error Handling',
            [get_called_class(), 'remotesnippet_error_settings_html'],
            'remotesnippets'
        );
        add_meta_box(
            'remotesnippet_help',
            'Help',
            [get_called_class(), 'remotesnippet_help_html'],
            'remotesnippets',
            'side'
        );
//https://wordpress.stackexchange.com/questions/5102/add-validation-and-error-handling-when-saving-custom-fields
    }

    public function remotesnippet_pre_post_update($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->post_type !== 'remotesnippets' || $screen->base !== 'post') {
            return;
        }

        if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'remotesnippets') {
            return;
        }

        $vals = [];
        foreach ($_POST as $field => $value) {
            if(strpos($field, 'remotesnippet_') === 0) {
                $vals[sanitize_key($field)] = sanitize_text_field($value);
            }
        }
        update_post_meta(
            $post_id,
            '_remotesnippets',
            $vals
        );

        $transKey = sprintf('remotesnippet_%s', sanitize_key($_POST['post_name']));
        delete_transient($transKey);

        // don't remove status for now
        // delete_post_meta($post_id, '_remotesnippets_error');
    }

    protected static function createOptionsHTML($options, $selected = '')
    {
        if (empty($selected)) {
            $selected = remotesnippets_array_key_first($options);
        }
        $html = '';
        foreach ($options as $n => $v) {
            $html .= sprintf('<option value="%s" %s>%s</option>',
                $n,
                ($selected == $n) ? 'selected' : '',
                $v
            );
        }
        return $html;
    }

    public static function remotesnippet_shortcode($post)
    {
        echo "<pre>[remotesnippet template=\"{$post->post_name}\"]</pre>";
    }

    public static function remotesnippet_help_html($post)
    {
        echo <<<HTML
        <h3>Variables</h3>
        Retrieved data is available as <code>data</code>.<br>
        The user is available as <code>wp_user</code>.<br>
        The post is available as <code>wp_post</code>.<br>
        <h3>Community</h3>
        Visit our community at <a href="https://forum.remotesnippets.com/" target="twig">forum.remotesnippets.com</a>.
        <h3>Twig Guide</h3>
        <a target="twig" href="https://twig.symfony.com/doc/3.x/templates.html">Full Guide</a><br>
        <a target="twig" href="https://twig.symfony.com/doc/3.x/tags/index.html">Tags</a><br>
        <a target="twig" href="https://twig.symfony.com/doc/3.x/filters/index.html">Filters</a><br>
        <a target="twig" href="https://twig.symfony.com/doc/3.x/functions/index.html">Functions</a><br>
        <h3>Extra functions</h3>
        <code>req('name')</code> HTTP request data.<br>
        <h3>Extra filters</h3>
        <code>|json_pretty</code> Pretty print JSON data.<br>
        <h3>Example - Loop</h3>
<pre>{% for post in data.posts %}
  {{ post.title }}&lt;br&gt;
{% endfor %}</pre>
<h3>Example - Condition</h3>
<pre>{% if user.name == 'Admin' %}
    ...
{% endif %}</pre>
<h3>Example - Filters</h3>
<pre>{{ 'my first car'|capitalize }}
{{ users|length }}
{{ data|json_pretty }}
{{ [1, 2, 3, 4]|first }}</pre>
HTML;

    }
    public static function remotesnippet_ds_settings_html($post)
    {
        $settings = self::getSettings($post->ID);
        $options_html = self::createOptionsHTML(static::$ds_options, $settings['remotesnippet_ds']);

        echo <<<HTML
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="remotesnippet_ds">Data Source</label>
                </th>
                <td>
                    <select name="remotesnippet_ds" id="remotesnippet_ds">
                        {$options_html}
                    </select>
                    <p class="description">Fetch data from an external source.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="remotesnippet_ds_url">Data Source Endpoint URL</label>
                </th>
                <td>
                    <input type="text" size="100" maxlength="256" name="remotesnippet_ds_url" id="remotesnippet_ds_url" value="{$settings['remotesnippet_ds_url']}">
                    <p class="description">URL where data is fetched from.</p>
                </td>
            </tr>
        </table>
HTML;
    }

    public static function remotesnippet_cache_settings_html($post)
    {
        $settings = self::getSettings($post->ID);

        $remotesnippet_cache_enable = ($settings['remotesnippet_cache_enable'] == 'Y') ? 'checked' : '';

        $remotesnippet_cache_timeout = $settings['remotesnippet_cache_timeout'];
        if (empty($remotesnippet_cache_timeout)) {
            $remotesnippet_cache_timeout = 60;
        }

        echo <<<HTML
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="remotesnippet_cache_enable">Enable Result Cache</label>
                </th>
                <td>
                    <input type="checkbox" name="remotesnippet_cache_enable" id="remotesnippet_cache_enable" value="Y" {$remotesnippet_cache_enable}> Enable
                    <p class="description">When enabled, results are cached according to the settings below.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="remotesnippet_cache_timeout">Cache Timeout</label>
                </th>
                <td>
                    <input type="text" name="remotesnippet_cache_timeout" id="remotesnippet_cache_timeout" size="6" maxlength="6" value="{$remotesnippet_cache_timeout}"> seconds.
                    <p class="description">Cache timeout in seconds. Results will be kept in cache for this long.
                        <br>This significantly reduces the amount of requests made to the remote endpoint.</p>
                </td>
            </tr>
        </table>
HTML;
    }


    public static function remotesnippet_error_settings_html($post)
    {
        $settings = self::getSettings($post->ID);

        $options_html = self::createOptionsHTML(static::$error_options, $settings['remotesnippet_error_handling']);

        echo <<<HTML
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="remotesnippet_error_handling">Error Handling</label>
                </th>
                <td>
                    <select name="remotesnippet_error_handling" id="remotesnippet_error_handling">
                        {$options_html}
                    </select>
                    <p class="description">What to do if something fails.</p>
                </td>
            </tr>
        </table>
HTML;
    }

}
