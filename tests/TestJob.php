<?php

class TestJob extends Job
{
    /**
     * Execute job
     * @return boolean
     */
    protected function _execute() {
        $randomHash = md5(rand());
        if (!empty($randomHash)) { 
            $this->log('success: ' . $randomHash);
        } else {
            $this->log('error...');
        }
        return true;
    }
}
