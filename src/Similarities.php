<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/tinysegmenter.php';

use GuzzleHttp\Client;
$segmenter = new TinySegmenterPHP();

class Similarities {
    static public function get_source_list()
    {
        return glob(__DIR__ . '/../data/*');
    }

    static public function get_all_text($array)
    {
        // 後で引数のバリデーションを追加
        $object = null;
        foreach($array as $i => $url) {
            $content = file_get_contents($url);
            $object[$i]['url'] = $url;
            $object[$i]['content'] = $content;
        }
        return $object;
    }

    static public function tagger($string)
    {
        // 後で引数のバリデーションを追加
        $segmenter = new TinySegmenterPHP();
        $array = $segmenter->segment($string);
        return $array;
    }

    static public function get_payload()
    {
        $payload = $_GET['payload'];
        if(preg_match("/^.*\,.*$/", $payload)) {
            $array = explode(',', $payload);
            return $array;
        } else {
            return [$payload];
        }
        
    }

    static public function get_wikipedia_body($title) {
        if(is_null($title)) {
            return false;
        }
    
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://ja.wikipedia.org/w/api.php',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
    
        $response = $client->get('?titles=' . urlencode($title) . '&format=json&prop=extracts&action=query&exsectionformat=plain');
        $data = json_decode($response->getBody());
        $pages = $data->query->pages;

        if(!reset($pages)->extract) {
            throw new Exception('The page which the title you entered is not found on Wikipedia ja.');
        }

        // echo '<pre>';
        // var_dump($pages);
        // echo '</pre>';
        $content = reset($pages)->extract;
        $plain = strip_tags($content);
        $removed_space = preg_replace("/\s|　/", "", $plain);
        return $removed_space;
    }
}