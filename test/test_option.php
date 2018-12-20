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
    $options->parse(["script.php", "-config", "./confog.json"]);
    assert($options['config'] == "./confog.json");
} catch (\Throwable $t) {
    assert(false, "exception non requise !!!");
}
