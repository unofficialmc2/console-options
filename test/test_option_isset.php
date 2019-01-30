<?php
declare (strict_types=1);

/**
 * User: Fabien Sanchez
 * Date: 19/12/2018
 * Time: 17:39
 */

use Console\Options\Option;
use Console\Options\OptionParser;

require __DIR__ . '/../vendor/autoload.php';

$options = new OptionParser([
    new Option('config', 'c'),
    new Option('name', 'n')
]);
$options->parse(['script.php', '-config', './confog.json']);

assert(isset($options['config']));
assert(isset($options['n']) === false);
assert(isset($options['x']) === false);
