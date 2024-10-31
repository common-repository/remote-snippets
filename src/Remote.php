<?php

namespace RemoteSnippets;

class Remote
{
    protected function getHeaders($settings)
    {
        return [
            'User-Agent' => PostMetaData::$ua
        ];
    }

    protected function makeRequest($settings, $headers)
    {
        return wp_remote_get($settings->remotesnippet_ds_url, ['headers' => $headers]);
    }

    public function fetchData($settings)
    {
        if ($settings->remotesnippet_ds == 'none') {
            return '';
        }

        $headers = $this->getHeaders($settings);

        $response = $this->makeRequest($settings, $headers);
        if (is_wp_error($response)) {
            $this->errMsg = $response->get_error_message();
            $this->errNo = -1;
            return false;
        }

        $http_code = wp_remote_retrieve_response_code($response);
        if ((int)($http_code/100) != 2) {
            $this->errMsg = "HTTP Error $http_code";
            $this->errNo = $http_code;
            return false;
        }

        $this->errMsg = '';
        $this->errNo = 0;

        $return = wp_remote_retrieve_body($response);
        return $return;
    }

    public function transformData($filedata, $settings)
    {
        $data = [];

        $userData = (array)wp_get_current_user()->data;
        unset($userData->user_pass);
        
        if (isset($userData['ID'])) {
            $userMeta = get_user_meta($userData['ID']);
            unset($userMeta['session_tokens']);
            $data['wp_user'] = array_merge($userData, $userMeta);
        }

        $postData = get_post(get_the_ID());
        $data['wp_post'] = $postData;

        $data['data_raw'] = $filedata;
        $data['data_format'] = $settings->remotesnippet_ds;

        switch($settings->remotesnippet_ds) {
            case 'url_json':
                $data['data'] = json_decode($filedata);
                break;
            case 'url_xml':
                $data['data'] = simplexml_load_string($filedata);
                break;
            case 'url_csv':
                // @TODO use https://www.php.net/manual/en/splfileobject.fgetcsv.php
                $rows = str_getcsv($filedata, "\n");
                foreach ($rows as &$row) {
                    $row = str_getcsv($row, ';');
                }
                $data['data'] = $rows;
                break;
            default:
                break;
        }
        return $data;
    }
}
