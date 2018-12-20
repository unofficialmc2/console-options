<?php
declare (strict_types = 1);
/**
 * User: Fabien Sanchez
 * Date: 19/12/2018
 * Time: 17:39
 */

use Options\OptionParser;
use Options\Option;

require __DIR__ . "/../vendor/autoload.php";

$options = new OptionParser([
    (new Option('config', 'c'))->setDefault("./confog.json")
]);

try {
    $options->parse(["script.php"]);
    assert($options['config'] == "./confog.json");
} catch (\Throwable $t) {
    assert(false, "exception non requise !!!");
}
