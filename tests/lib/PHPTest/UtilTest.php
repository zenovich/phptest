<?php
require_once __DIR__ . '/../../TestHelper.php';
require_once PHPTEST_PATH . 'Util.php';
require_once PHPTEST_PATH . 'Spy.php';

class PHPTest_UtilTest extends PHPTest_TestCase {
    public function providerGetRandomNumber() {
        return array(
            array(NULL, NULL, 0, getrandmax()),
            array($firstParam = 1, NULL, $firstParam, getrandmax()),
            array($firstParam = 1, $secondParam = 2, $firstParam, $secondParam)
        );
    }

    /**
     * @dataProvider providerGetRandomNumber
     */
    public function testGetRandomNumber($firstParam, $secondParam, $expectedFirst, $expectedSecond) {
        $expectedResult = rand();
        $spy = PHPTest_Spy::interceptFunction('rand');
        $spy->setReturnValue($expectedResult);

        $result = PHPTest_Util::getRandomNumber($firstParam, $secondParam);
        $callParams = $spy->getCallParams();

        $this->assertEquals(array(array($expectedFirst, $expectedSecond)), $callParams, 'Wrong params');
        $this->assertEquals($expectedResult, $result, 'Wrong result');
    }

    public function testMakeException() {
        $stub = PHPTest_Spy::interceptMethod('PHPTest_Util', 'getExceptionClass');
        $stub->setReturnValue($expectedResult = "stdClass");

        $result = PHPTest_Util::makeException($param1 = "PARAM1", $param2 = "PARAM2", $param2 = "PARAM3");
        $callParams = $stub->getCallParams();

        $this->assertEquals(array(array(__CLASS__)), $callParams, 'Wrong calling class detecled');
        $this->assertType($expectedResult, $result, 'Wrong type of result');
    }

    public function testGetExceptionClassWithEmptyParam() {
        $result = $this->callMethod('PHPTest_Util', 'getExceptionClass', '');
        $this->assertEquals('Exception', $result, 'Wrong default Exception class');
    }

    public function testGetExceptionClassNormal() {
        include '_files/TestGetExceptionClassNormal.php'; // Correct class having own exception class

        $result = $this->callMethod('PHPTest_Util', 'getExceptionClass', 'TestGetExceptionClassNormal');
        $this->assertEquals('TestGetExceptionClassNormal_Exception', $result, 'Wrong exception class');
    }

    public function testGetExceptionClassNormalWithExceptionClassDefined() {
        include '_files/TestGetExceptionClassNormalWithExceptionClassDefined.php'; // File contains both class and exception class

        $result = $this->callMethod('PHPTest_Util', 'getExceptionClass', 'TestGetExceptionClassNormalWithExceptionClassDefined');
        $this->assertEquals('TestGetExceptionClassNormalWithExceptionClassDefined_Exception', $result, 'Wrong exception class');
    }

    /**
     * Also tests finding the parent-level exception
     * @expectedException PHPTest_Exception
     */
    public function testGetExceptionClassWithIncorrectExceptionFile() {
        include '_files/TestGetExceptionClassWithIncorrectExceptionFile.php'; // Correct class having incorrect exception file

        $result = $this->callMethod('PHPTest_Util', 'getExceptionClass', 'TestGetExceptionClassWithIncorrectExceptionFile');
    }
}