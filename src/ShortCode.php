<?php

namespace RemoteSnippets;

use \RS_Vendor\Twig\Extra\Markdown\DefaultMarkdown;
use \RS_Vendor\Twig\Extra\Markdown\MarkdownRuntime;
use \RS_Vendor\Twig\RuntimeLoader\RuntimeLoaderInterface;
use \RS_Vendor\Twig\Extra\Markdown\MarkdownExtension;

class ShortCode {

    public static function getNamespace()
    {
        $components = explode('\\', get_called_class());
        $z = array_pop($components);
        return implode('\\', $components);
    }

    private static function setStatus($post_id, $status, $details = '', $isError = false)
    {
        $oldStatus = get_post_meta($post_id, '_remotesnippets_error', true);
        if ($oldStatus['status'] != $status) {
            update_post_meta($post_id, '_remotesnippets_error', [
                'status' => $status,
                'ts' => time(),
                'details' => $details
            ]);
            update_post_meta($post_id, '_remotesnippets_error_status', $isError ? 'Error' : '');
            delete_transient('remotesnippet_errors'); // make sure they get recounted
        }
    }

    protected static function handleError($settings, $errMsg, $content)
    {
        if ($settings->remotesnippet_error_handling == 'error') {
            return "RemoteSnippets Error: ".$errMsg;
        } else {
            return $content;
        }
    }

    public static function handleShortcode($attr = [], $content = null)
    {
        if (!isset($attr['template']) || empty($attr['template'])) {
            return "RemoteSnippets Error: 'template' attribute must be set for shortcode 'remotesnippet'.";
        }

        $template = $attr['template'];

        $transKey = sprintf('remotesnippet_%s', sanitize_key($template));
        if (false === ($renderedContent = get_transient($transKey))) {
            // this code runs when there is no valid transient set
            $templateName = 'RemoteSnippet '.$template;
            $post = get_page_by_path($template, OBJECT, 'remotesnippets');
            if (empty($post)) {
                return "RemoteSnippets Error: template '$template' does not exist.";
            }

            $settings = (object)get_post_meta($post->ID, '_remotesnippets', true);
            $settings->post = $post;

            $class = self::getNamespace() . '\Remote';
            $remote = new $class;
            $filedata = $remote->fetchData($settings);
            if ($filedata === false) {
                self::setStatus($post->ID, 'Remote Request Error', $remote->errMsg, true);
                return static::handleError($settings, $remote->errMsg, $content);
            }

            $data = $remote->transformData($filedata, $settings);


            $loader = new \RS_Vendor\Twig\Loader\ArrayLoader([
                $templateName => $post->post_content,
             ]);
             $twig = new \RS_Vendor\Twig\Environment($loader, [
                 'strict_variables' => false
             ]);

             $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
                 public function load($class) {
                     if (MarkdownRuntime::class === $class) {
                         return new MarkdownRuntime(new DefaultMarkdown());
                     }
                 }
             });
             $twig->addExtension(new MarkdownExtension());

             $twig->addFunction(new \RS_Vendor\Twig\TwigFunction('req', function($name = '', $default = null) {
                 if (!array_key_exists($name, $_REQUEST)) {
                     return $default;
                 }
                 return wp_unslash($_REQUEST[$name]);
             }));

             $filter = new \RS_Vendor\Twig\TwigFilter('json_pretty', function ($string) {
                 return json_encode($string, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
             });
             $twig->addFilter($filter);



             try {
                 $renderedContent = $twig->render($templateName, $data);
                 self::setStatus($post->ID, 'Ok', '', false);
             }
             catch (\Exception $e) {
                 self::setStatus($post->ID, 'Template Syntax Error', $e->getMessage(), true);
                 return static::handleError($settings, $e->getMessage(), $content);
             }

             if (isset($settings->remotesnippet_cache_enable) && ($settings->remotesnippet_cache_enable == 'Y')) {
                 $timeout = (int)$settings->remotesnippet_cache_timeout;
                 if ($timeout > 0) {
                     set_transient($transKey, $renderedContent, $timeout);
                 }
             }
         } // end transient fail

         return $renderedContent;
    }
}
