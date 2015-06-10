<?php

class PHPTest_Util {
    /**
     * Returns a random integer number
     *
     * @param integer $from
     * @param integer $to
     * @return integer
     */
    public static function getRandomNumber($from = 0, $to = NULL) {
        if ($from === NULL) {
            $from = 0;
        }
        if ($to === NULL) {
            $to = getrandmax();
        }
        return rand($from, $to);
    }

    /**
     * Finds the class, does include and creates an exception object
     *
     * @param  string         $message
     * @param  integer        $code
     * @param  Exception|null $previous
     * @return Exception
     */
    public static function makeException($message = "", $code = 0, $previous = NULL) {
        $trace = debug_backtrace();

        $callingClass = '';
        if (!empty($trace[1]['class'])) {
            $callingClass = $trace[1]['class'];
        }

        $excClassName = self::getExceptionClass($callingClass);

        return new $excClassName($message, $code, $previous);
    }

    private static function getExceptionClass($callingClass) {
        $excClassName = "";

        if ($callingClass) {
            $reflClass = new ReflectionClass($callingClass);
            $classDir = dirname($reflClass->getFileName());
            $classNameParts = explode('_', $callingClass);

            while (count($classNameParts)) {
                $currentExcClassName = implode('_', $classNameParts) . '_Exception';

                if (class_exists($currentExcClassName)) {
                    $excClassName = $currentExcClassName;
                    break;
                }

                $excFileName =  $classDir . '/' . end($classNameParts) . '/Exception.php';
                if (file_exists($excFileName)) {
                    require_once $excFileName;
                    if (!class_exists($currentExcClassName)) {
                        throw self::makeException("No class '$currentExcClassName' in file '$excFileName'");
                    }
                    $excClassName = $currentExcClassName;
                    break;
                }

                array_pop($classNameParts);
                $classDir = substr($classDir, 0, strrpos($classDir, '/'));
            }
        }

        if (!$excClassName) {
            $excClassName = 'Exception';
        }

        return $excClassName;
    }
}
