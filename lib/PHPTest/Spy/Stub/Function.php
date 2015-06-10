<?php
require_once __DIR__ . '/../Stub.php';
require_once __DIR__ . '/../../Spy.php';

class PHPTest_Spy_Stub_Function extends PHPTest_Spy_Stub {
    protected $originName;
    protected $copyName;

    public function __construct($name) {
        $this->originName = $name;
        $this->copyName = uniqid(__CLASS__ . '_' . $name);

        PHPTest_Spy::registerStub($this->copyName, $this);

        if (!runkit_function_rename($name, $this->copyName)) {
            throw PHPTest_Util::makeException('Cannot rename old function "' . $name . '" -> "' . $this->copyName . '"');
        }

        if (!function_exists($this->copyName)) {
            throw PHPTest_Util::makeException('Function does not exist after renaming: "' . $this->copyName . '"');
        }

        if (!runkit_function_add($name, '', "return PHPTest_Spy::getStub('" . $this->copyName . "')->handleCall(func_get_args());")) {
            runkit_function_rename($this->copyName, $name);
            throw PHPTest_Util::makeException('Cannot add function "' . $name . '"');
        }
    }

    public function restore() {
        if (!runkit_function_remove($this->originName)) {
            throw PHPTest_Util::makeException('Cannot remove redefined function "' . $this->originName . '"');
        }

        if (!runkit_function_rename($this->copyName, $this->originName)) {
            throw PHPTest_Util::makeException('Cannot rename old function back "' . $this->copyName . '"' . ' -> "' . $this->originName . '"');
        }

        PHPTest_Spy::removeStub($this->copyName);
    }
}
