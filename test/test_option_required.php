<?php
declare (strict_types = 1);
/**
 * User: Fabien Sanchez
 * Date: 19/12/2018
 * Time: 17:39
 */

use Console\Options\OptionParser;
use Console\Options\Option;

require __DIR__ . "/../vendor/autoload.php";

$options = new OptionParser([
    (new Option('config', 'c'))->setRequired()
]);

try {
    $options->parse(["script.php", "./confog.json"]);
    assert(false, "exception requise !!!");
} catch(\Throwable $t){
    assert($options[0] == "./confog.json");
}
