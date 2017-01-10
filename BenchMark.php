<?php

/*
 * Check BenchMark
 */

class benchmark {

    public $START;
    public $END;
    public $MEMORY;
    public $MEMORY_PEAK;
    public $TIME;
    public $ARR_VAL;

    private function the_microtime() {
        $time = explode(" ", microtime(true));
        return $time[0] + $time[0];
    }

    private function mem_in($para) {
        return substr($para / 1024, 0, 6) . 'K';
    }

    public function bm_start() {
        $this->START = microtime(true);
        $this->MEMORY = memory_get_usage();
        $this->MEMORY_PEAK = memory_get_peak_usage();
    }

    public function bm_get() {
        $this->TIME = substr((microtime(true) - $this->START), 0, 6);
        $this->MEMORY = $this->mem_in(memory_get_usage() - $this->MEMORY) . '-' . $this->mem_in(memory_get_peak_usage() - $this->MEMORY_PEAK);

        $this->ARR_VAL = [
            'Render' => $this->TIME . 'S',
            'Memory' => $this->MEMORY
        ];
        return $this->ARR_VAL;
    }

    function __destruct() {
        unset($this);
    }

}

?>