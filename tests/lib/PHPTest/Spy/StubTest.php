<?php
require_once __DIR__ . '/../../../TestHelper.php';
require_once PHPTEST_PATH . 'Spy.php';

class PHPTest_Spy_StubTest extends PHPTest_TestCase {
    public function testReturnValue() {
        $stub = PHPTest_Spy::interceptFunction('mail');
        $stub->setReturnValue($expected = "TEST");

        $result = mail();

        $this->assertEquals($expected, $result, 'Wrong result');
    }

    public function testException() {
        $stub = PHPTest_Spy::interceptFunction('mail');
        $stub->setException($expected = new Exception('TEST'));

        try {
            $result = mail();
        } catch (Exception $e) {
        }

        $this->assertEquals($expected, $e, 'Wrong exception');
    }

    public function testGetCallParams() {
        $stub = PHPTest_Spy::interceptFunction('mail');
        $args1 = array();
        $args2 = array($exp1 = PHPTest_Util::getRandomNumber(), $exp2 = PHPTest_Util::getRandomNumber());

        mail();
        mail($exp1, $exp2);

        $this->assertEquals(array($args1, $args2), $stub->getCallParams(), 'Wrong result');
    }
}
