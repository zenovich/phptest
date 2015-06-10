<?php
require_once __DIR__ . '/../../../../TestHelper.php';
require_once PHPTEST_PATH . 'Spy/Stub/Function.php';

class PHPTest_Spy_Stub_FunctionTest extends PHPTest_TestCase {
    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testConstructCantAdd() {
        runkit_function_rename('runkit_function_add', 'runkit_function_add_old');
        $this->addCleanupCallback(function() {runkit_function_rename('runkit_function_add_old', 'runkit_function_add');});
        runkit_function_add_old('runkit_function_add', '', 'return false;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_add');});

        runkit_function_rename('runkit_function_rename', 'runkit_function_rename_old');
        $this->addCleanupCallback(function() {runkit_function_rename_old('runkit_function_rename_old', 'runkit_function_rename');});
        runkit_function_add_old('runkit_function_rename', '', 'return true;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_rename');});

        $stub = new PHPTest_Spy_Stub_Function('sprintf');
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testConstructNonexistentFunction() {
        runkit_function_rename('runkit_function_rename', 'runkit_function_rename_old');
        $this->addCleanupCallback(function() {runkit_function_rename_old('runkit_function_rename_old', 'runkit_function_rename');});
        runkit_function_add('runkit_function_rename', '', 'return false;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_rename');});

        $stub = new PHPTest_Spy_Stub_Function('nonexistent_function');
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testRestoreCantRemove() {
        runkit_function_rename('runkit_function_add', 'runkit_function_add_old');
        $this->addCleanupCallback(function() {runkit_function_rename('runkit_function_add_old', 'runkit_function_add');});
        runkit_function_add_old('runkit_function_add', '', 'return true;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_add');});

        runkit_function_rename('runkit_function_rename', 'runkit_function_rename_old');
        $this->addCleanupCallback(function() {runkit_function_rename_old('runkit_function_rename_old', 'runkit_function_rename');});
        runkit_function_add_old('runkit_function_rename', '', 'return true;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_rename');});

        runkit_function_rename_old('runkit_function_remove', 'runkit_function_remove_old');
        $this->addCleanupCallback(function() {runkit_function_rename_old('runkit_function_remove_old', 'runkit_function_remove');});
        runkit_function_add_old('runkit_function_remove', '', 'return false;');
        $this->addCleanupCallback(function() {runkit_function_remove_old('runkit_function_remove');});

        $stub = new PHPTest_Spy_Stub_Function('sprintf');

        $stub->restore();
    }

    /**
     * @expectedException PHPTest_Spy_Exception
     */
    public function testRestoreCantRename() {
        runkit_function_rename('runkit_function_add', 'runkit_function_add_old');
        $this->addCleanupCallback(function() {runkit_function_rename('runkit_function_add_old', 'runkit_function_add');});
        runkit_function_add_old('runkit_function_add', '', 'return true;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_add');});

        runkit_function_rename('runkit_function_rename', 'runkit_function_rename_old');
        $this->addCleanupCallback(function() {runkit_function_rename_old('runkit_function_rename_old', 'runkit_function_rename');});
        runkit_function_add_old('runkit_function_rename', '', 'return true;');
        $this->addCleanupCallback(function() {runkit_function_remove('runkit_function_rename');});

        runkit_function_rename_old('runkit_function_remove', 'runkit_function_remove_old');
        $this->addCleanupCallback(function() {runkit_function_rename_old('runkit_function_remove_old', 'runkit_function_remove');});
        runkit_function_add_old('runkit_function_remove', '', 'return true;');
        $this->addCleanupCallback(function() {runkit_function_remove_old('runkit_function_remove');});

        $stub = new PHPTest_Spy_Stub_Function('sprintf');

        runkit_function_redefine('runkit_function_rename', '', 'return false;');

        $stub->restore();
    }
} 