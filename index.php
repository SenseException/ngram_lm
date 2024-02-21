<?php

ini_set('memory_limit', '4G');
ini_set('max_execution_time', '300');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use Senseexception\NgramLm\LanguageModel\Operator\AcidBurn86 as SentenceGenerator;
use Senseexception\NgramLm\LanguageModel\Trainer\AcidBurn86 as ModelTrainer;

$eol = (isset($_SERVER['SHELL'])) ? PHP_EOL : "<br />";

$ngrams = 6;

$modelSource = new \Senseexception\NgramLm\ModelSource\File('trained');

// Usage
if (!file_exists('trained')) {
    $started = date('H:i:s');
    echo "creating file 'trained'" . $eol;
    echo "started training at:" . date('H:i:s') . $eol;
    echo $eol;
    echo $eol;

    $modelTrainer = new ModelTrainer($modelSource, $ngrams);
    $modelTrainer->train('train-input.txt');

    echo "finished training at:" . date('H:i:s') . $eol;
    // calculate time difference
    $ended = date('H:i:s');
    $diff = abs(strtotime($ended) - strtotime($started));
    echo $eol;
    echo $eol;
    echo "time taken: " . gmdate('H:i:s', $diff) . $eol;

    echo $eol;
    echo $eol;

    unset($modelTrainer);
    gc_collect_cycles();
}
$started = date('H:i:s');

$sentenceGenerator = new SentenceGenerator($modelSource, $ngrams);
$sentenceGenerator->loadModel();

$ended = date('H:i:s');
$diff = abs(strtotime($ended) - strtotime($started));
echo $eol;
echo "the model loaded in: " . gmdate('H:i:s', $diff) . $eol;
echo "---------------". $eol;

echo $eol;

$words = ['yes', 'voldemort', 'harry', 'hogwarts'];

foreach ($words as $word) {
    $started = date('H:i:s:u');
    echo $sentenceGenerator->generateSentence($word, 50). $eol;
    $ended = date('H:i:s:u');
    $diff = abs(strtotime($ended) - strtotime($started));
    echo "". $eol;
}
unset($sentenceGenerator);
gc_collect_cycles();
exit;
?>
