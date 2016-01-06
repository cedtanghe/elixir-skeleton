<?php

namespace Site\Test;

use PHPUnit_Framework_TestCase;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $output = 'ready';
        $this->assertEquals('ready', $output);
    }
}
