<?php

require_once __DIR__ . '/Spy/Stub.php';
require_once __DIR__ . '/Spy/Stub/Function.php';
require_once __DIR__ . '/Spy/Stub/Method.php';

class PHPTest_Spy {
    private static $stubs = array();

    /**
     * @return PHPTest_Spy_Stub_Function
     * @throws PHPTest_Spy_Exception
     */
    public static function interceptFunction($name) {
        $newStub = new PHPTest_Spy_Stub_Function($name);

        self::registerStubInPhpUnitTestCase($newStub);

        return $newStub;
    }

    /**
     * @return PHPTest_Spy_Stub_Method
     * @throws PHPTest_Spy_Exception
     */
    public static function interceptMethod($className, $name) {
        $newStub = new PHPTest_Spy_Stub_Method($className, $name);

        self::registerStubInPhpUnitTestCase($newStub);

        return $newStub;
    }

    /**
     * @return PHPTest_Spy_Stub
     * @throws PHPTest_Spy_Stub_Exception
     */
    public static function getStub($name) {
        if (!isset(self::$stubs[$name])) {
            throw PHPTest_Util::makeException('No such stub: "' . $name . "'");
        }
        return self::$stubs[$name];
    }

    /**
     * @throws PHPTest_Spy_Stub_Exception
     */
    public static function removeStub($name) {
        if (!isset(self::$stubs[$name])) {
            throw PHPTest_Util::makeException('No such stub: "' . $name . "'");
        }
        unset(self::$stubs[$name]);
    }

    /**
     * @throws PHPTest_Spy_Stub_Exception
     */
    public static function registerStub($name, PHPTest_Spy_Stub $stub) {
        if (isset(self::$stubs[$name])) {
            throw PHPTest_Util::makeException('Stub already exists: "' . $name . "'");
        }
        self::$stubs[$name] = $stub;
    }

    protected static function registerStubInPhpUnitTestCase(PHPTest_Spy_Stub $stub) {
        // Register cleanup callback in TestCase
        $backtrace = debug_backtrace();

        for ($i = 2; $i < count($backtrace); $i++) {
            if (!empty($backtrace[$i]['object']) && $backtrace[$i]['object'] instanceof PHPUnit_Framework_TestCase) {
                if (version_compare(PHP_VERSION, '5.3', '>')) {
                    $reflProp = NULL;
                    if (property_exists($backtrace[$i]['object'], 'backupStaticAttributesBlacklist')) {
                        $reflProp = new ReflectionProperty($backtrace[$i]['object'], 'backupStaticAttributesBlacklist');
                        $reflProp->setAccessible(true);
                        $value = $reflProp->getValue($backtrace[$i]['object']);
                        if (!in_array(__CLASS__, $value)) {
                            $value[] = __CLASS__;
                            $reflProp->setValue($backtrace[$i]['object'], $value);
                        }
                    }
                }
                $backtrace[$i]['object']->addCleanupCallback(array($stub, 'restore'));
                break;
            }
        }
    }
}
