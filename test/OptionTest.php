<?php

namespace Console\Options;

use Console\Options\Option;

class OptionTest extends TestCase
{
    public function testCreationOption(): void
    {
        $this->expectNotToPerformAssertions();
        $opt = (new Option('config', 'c'))->setRequired();
    }
}
