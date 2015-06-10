<?php

require_once 'PHPUnit/Framework/TestCase.php';

class PHPTest_TestCase extends PhpUnit_Framework_TestCase {
    protected $cleanupCallbacks;

    /**
     * Runs the bare test sequence.
     */
    public function runBare() {
        // Clean up callbacks.
        $this->cleanupCallbacks = array();

        try {
            $result = parent::runBare();
        } catch (Exception $e) {
        }

        // Apply cleanup callbacks.
        $callbacksReversed = array_reverse($this->cleanupCallbacks);
        foreach ($callbacksReversed as $callback) {
            try {
                call_user_func($callback['function'], $callback['data']);
            } catch (Exception $ce) {
                if (empty($e)) {
                    $e = $ce;
                }
            }
        }

        if (!empty($e)) {
            throw $e;
        }

        return $result;
    }

    /**
     * Registers a cleanup callback. This method may be called within the test
     * or utility functions or methods, which are friends of the test-case class
     * and find the test-case object in the call stack (that is why this method is public).
     * Cleanup callbacks are actions which will mandatoryly be called by the runBare
     * regardless from the test's result.
     *
     * @param mixed $callback
     * @param mixed $data
     * @since Method available since Release 3.5.1
     */
    public function addCleanupCallback($callback, $data = NULL)
    {
        $this->cleanupCallbacks[] = array('function' => $callback, 'data' => $data);
    }

    public static function callMethod($classOrObject, $methodName) {
        $args = func_get_args();
        $args = array_slice($args, 2);

        $reflMethod = new ReflectionMethod($classOrObject, $methodName);
        $reflMethod->setAccessible(TRUE);

        return $reflMethod->invokeArgs(is_object($classOrObject) ? $classOrObject : NULL, $args);
    }

    public static function writeAttribute($classOrObject, $propName, $value) {
        $reflProp = new ReflectionProperty($classOrObject, $propName);
        $reflProp->setAccessible(TRUE);

        return $reflProp->setValue(is_object($classOrObject) ? $classOrObject : NULL, $value);
    }

    protected function getFullMock($className, $mockMethods = array(), $testDoubleClass = '') {
        return $this->getMock($className, $mockMethods, array(), $testDoubleClass, FALSE, FALSE, FALSE);
    }

    /**
     * Stub Zend_Db adapter
     */
    public function getZendDbStub($className = 'Zend_Db_Adapter_Abstract') {
        if (!class_exists('Zend_Db_Adapter_Abstract')) {
            require_once 'Zend/Db/Adapter/Abstract.php';
        }

        $reflClass = new ReflectionClass($className);
        $reflMethods = $reflClass->getMethods();
        $methods = array();
        foreach ($reflMethods as $reflMethod) {
            $name = $reflMethod->getName();
            // skip quote methods
            if (substr($name, 0, 5) != 'quote' &&
                substr($name, 0, 6) != '_quote' && strpos($name, 'uoteIdentifier') === false) {
                $methods[] = $name;
            }
        }

        $stubClassName = $className . '_' . uniqid('Stub');
        $stub = $this->getMock($className, $methods, array(), $stubClassName, false, false, false);
        return $stub;
    }

}
