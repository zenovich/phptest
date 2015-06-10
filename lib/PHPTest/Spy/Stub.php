<?php

class PHPTest_Spy_Stub {
    protected $returnValue = NULL;
    protected $callParams = array();
    protected $alwaysReturnStoredValue = FALSE;
    protected $exception = NULL;
    protected $alwaysThrowStoredException = FALSE;

    public function handleCall($args) {
        $this->callParams[] = $args;

        if ($this->alwaysThrowStoredException) {
            throw $this->exception;
        }

        if ($this->alwaysReturnStoredValue) {
            return $this->returnValue;
        }
    }

    /**
     * Set the return value for every call
     */
    public function setReturnValue($value) {
        $this->returnValue = $value;
        $this->alwaysReturnStoredValue = true;
    }

    /**
     * Set the exception to throw on every call
     */
    public function setException($exception) {
        $this->exception = $exception;
        $this->alwaysThrowStoredException = true;
    }

    /**
     * Returns an array containing params for all calls (one row for call)
     * @return array
     */
    public function getCallParams() {
        return $this->callParams;
    }
}
