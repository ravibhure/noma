<?php
require_once "PHPUnit/Autoload.php";
 
class defaultTest extends PHPUnit_Framework_TestCase {

    public function testPHPUNIT() {
 
        $expected = "Hello world!";
        $actual = $expected;
        $this->assertEquals($expected, $actual);
    }

}

