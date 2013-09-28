<?php

namespace pallo\library\dependency\argument;

use pallo\library\dependency\DependencyCallArgument;

use \PHPUnit_Framework_TestCase;

class ArrayArgumentParserTest extends PHPUnit_Framework_TestCase {

    public function testGetValue() {
        $parser = new ArrayArgumentParser();

        $data = array('var1' => 'value', 'var2' => 'value');
        $argument = new DependencyCallArgument('name', 'array', $data);

        $result = $parser->getValue($argument);

        $this->assertEquals($data, $result);
    }

}