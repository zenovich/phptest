<?php
require_once __DIR__ . '/../../../../TestHelper.php';
require_once PHPTEST_PATH . 'Spy/Stub/Method.php';

class PHPTest_Spy_Stub_MethodTest extends PHPTest_TestCase {
    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testConstructCantAdd() {
        $renameStub = PHPTest_Spy::interceptFunction('runkit_method_rename');
        $renameStub->setReturnValue(true);
        $addStub = PHPTest_Spy::interceptFunction('runkit_method_add');
        $addStub->setReturnValue(false);

        $stub = new PHPTest_Spy_Stub_Method('TestClass', 'testMethod');
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testConstructNonexistentMethod() {
        $renameStub = PHPTest_Spy::interceptFunction('runkit_method_rename');
        $renameStub->setReturnValue(false);

        $stub = new PHPTest_Spy_Stub_Method('UnexistentClass', 'nonexistentMethod');
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testRestoreCantRemove() {
        $renameStub = PHPTest_Spy::interceptFunction('runkit_method_rename');
        $renameStub->setReturnValue(true);
        $addStub = PHPTest_Spy::interceptFunction('runkit_method_add');
        $addStub->setReturnValue(true);
        $removeStub = PHPTest_Spy::interceptFunction('runkit_method_remove');
        $removeStub->setReturnValue(false);

        $stub = new PHPTest_Spy_Stub_Method('TestClass', 'testMethod');

        $stub->restore();
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testRestoreCantRename() {
        $renameStub = PHPTest_Spy::interceptFunction('runkit_method_rename');
        $renameStub->setReturnValue(true);
        $addStub = PHPTest_Spy::interceptFunction('runkit_method_add');
        $addStub->setReturnValue(true);
        $removeStub = PHPTest_Spy::interceptFunction('runkit_method_remove');
        $removeStub->setReturnValue(true);

        $stub = new PHPTest_Spy_Stub_Method('TestClass', 'testMethod');

        $renameStub->setReturnValue(false);

        $stub->restore();
    }
} 