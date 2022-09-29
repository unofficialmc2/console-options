<?php

namespace Console\Options;

use Console\Options\OptionParser;

class OptionParserTest extends TestCase
{

    /**
     * @return void
     */
    public function testPaseSimpleOption(): void
    {
        $options = new OptionParser([
            (new Option('config', 'c'))->setRequired()
        ]);
        $options->parse(["script.php", "-config", "./confog.json"]);
        self::assertEquals("./confog.json", $options['config']);
    }

    /**
     * @return void
     */
    public function testParseWithDefault(): void
    {
        $options = new OptionParser([
            (new Option('config', 'c'))->setDefault("./confog.json")
        ]);
        $options->parse(["script.php"]);
        self::assertEquals("./confog.json", $options['config']);
    }

    /**
     * @return void
     */
    public function testOptionIsSet(): void
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

    /**
     * @return void
     */
    public function testOptionIsRequired(): void
    {
        $this->expectException(\LogicException::class);
        $options = new OptionParser([
            (new Option('config', 'c'))->setRequired()
        ]);
        $options->parse(["script.php", "./confog.json"]);
    }
}
