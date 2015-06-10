<?php
require_once __DIR__ . '/../../TestHelper.php';
require_once PHPTEST_PATH . 'Spy.php';

class PHPTest_SpyTest extends PHPTest_TestCase {
    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testGetStubNonExistent() {
        PHPTest_Spy::getStub('nonexistent');
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testRemoveStubNonExistent() {
        PHPTest_Spy::removeStub('nonexistent');
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testRegisterStubSecondTime() {
        $stub = new PHPTest_Spy_Stub;
        PHPTest_Spy::registerStub('testStub', $stub);
        $this->addCleanupCallback(function() {PHPTest_Spy::removeStub('testStub');});

        PHPTest_Spy::registerStub('testStub', $stub);
    }

    public function testRegisterStubInPhpUnitTestCaseWithEmptyBacktrace() {
        $this->backupStaticAttributesBlacklist[] = 'PHPTest_Spy';

        $stub = PHPTest_Spy::interceptFunction('debug_backtrace');
        $stub->setReturnValue(array(array(), array(), array())); // Three rows
        $this->addCleanupCallback(array($stub, 'restore'));

        $this->callMethod('PHPTest_Spy', 'registerStubInPhpUnitTestCase', $stub);
    }

}