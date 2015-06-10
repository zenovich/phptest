<?php
require_once __DIR__ . '/../Stub.php';

class PHPTest_Spy_Stub_Method extends PHPTest_Spy_Stub {
    protected $originName;
    protected $copyName;
    protected $className;

    public function __construct($className, $name) {
        $this->originName = $name;
        $this->className = $className;
        $this->copyName = uniqid(__CLASS__ . '_' . $className . '_' . $name);

        PHPTest_Spy::registerStub($this->copyName, $this);

        if (!runkit_method_rename($className, $name, $this->copyName)) {
            throw PHPTest_Util::makeException('Cannot rename old method "' . $className . '::' . $name .
                                              '"' . ' -> "' . $className . '::' . $this->copyName . '"');
        }

        if (!runkit_method_add($className, $name, '', "return PHPTest_Spy::getStub('" . $this->copyName . "')->handleCall(func_get_args());")) {
            throw PHPTest_Util::makeException('Cannot add method "' . $className . '::' . $name . '"');
        }
    }

    public function restore() {
        if (!runkit_method_remove($this->className, $this->originName)) {
            throw PHPTest_Util::makeException('Cannot remove redefined method "' . $this->className . '::' . $this->originName . '"');
        }

        if (!runkit_method_rename($this->className, $this->copyName, $this->originName)) {
            throw PHPTest_Util::makeException('Cannot rename old method back "' . $this->className . '::' . $this->originName .
                                              '"' . ' -> "' . $this->className . '::' . $this->originName . '"');
        }

        PHPTest_Spy::removeStub($this->copyName);
    }
}