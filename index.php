<?php
error_reporting(E_ALL);
ini_set("display_error", true);
ini_set("error_reporting", E_ALL);

require_once 'vendor/autoload.php';

try {
    $table = (new \App\CliInputReader())->readTable($argv);

    $gameResult = $table->gameResult();

    echo (new \App\GameResultCliFormatter($gameResult))->format();

    return;
} catch (\Exception $e) {
    echo 'error: '.$e->getMessage();
    die(1);
}
