<?php

namespace Console\Options;

use Console\Options\OptionParser;

class OptionParserTest extends TestCase
{

    public function testPaseSimpleOption()
    {
        $options = new OptionParser([
            (new Option('config', 'c'))->setRequired()
        ]);
        $options->parse(["script.php", "-config", "./confog.json"]);
        self::assertEquals("./confog.json", $options['config']);
    }

    public function testParseWithDefault()
    {
        $options = new OptionParser([
            (new Option('config', 'c'))->setDefault("./confog.json")
        ]);
        $options->parse(["script.php"]);
        self::assertEquals("./confog.json", $options['config']);
    }

    public function testOptionIsSet()
    {
        $options = new OptionParser([
            new Option('config', 'c'),
            new Option('name', 'n')
        ]);
        $options->parse(['script.php', '-config', './confog.json']);
        self::assertTrue(isset($options['config']));
        self::assertFalse(isset($options['n']));
        self::assertFalse(isset($options['x']));
    }

    public function testOptionIsRequired()
    {
        $this->expectException(\LogicException::class);
        $options = new OptionParser([
            (new Option('config', 'c'))->setRequired()
        ]);
        $options->parse(["script.php", "./confog.json"]);
    }

}
