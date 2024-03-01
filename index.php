<?php
require 'vendor/autoload.php';
require 'src/Similarities.php';

use GuzzleHttp\Client;
use Fieg\Bayes\Classifier;
use Fieg\Bayes\Tokenizer\WhitespaceAndPunctuationTokenizer;

$tokenizer = new WhitespaceAndPunctuationTokenizer();
$classifier = new Classifier($tokenizer);


$s = new Similarities;
$list = $s->get_source_list();

$source_object = $s->get_all_text($list);


$base = file_get_contents(__DIR__ . '/base/base.txt');

// $array_base = $s->tagger($base);

$result = null;

foreach($source_object as $i => $source) {
    
        similar_text($base,$source['content'],$percent);
        $result[$i]['url'] = $source_object[$i]['url'];
        $result[$i]['similarity'] = $percent;
}

$similarityArray = array_column($result, 'similarity');
array_multisort($similarityArray, SORT_DESC, $result);

echo '<pre>';
var_dump($result);
echo '</pre>';


// $text1 = <<<EOL
// 阪急8000系は、阪急電鉄が所有する鉄道車両の形式である。
// 神戸線と宝塚線で運用されている。
// EOL;

// $text2 = <<<EOL
// 阪急9300系は、阪急電鉄が所有する鉄道車両の形式である。
// 京都線で運用されており、主に特急列車として運用されている。
// EOL;

// $text3 = <<<EOL
// 阪神9300系は、阪神電鉄が所有する鉄道車両の形式である。
// 阪神梅田から、山陽姫路の区間で、主に、直通特急を含む急行系列の種別に充当される。
// 阪神なんば線には入線しない。
// EOL;

// echo similar_text($text1,$text3);
// echo $percent;

// echo '<pre>';
// echo '</pre>';
// $payload = Similarities::get_payload();





// echo '<pre>';
// var_dump($result);
// echo '</pre>';