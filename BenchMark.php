<?php

/*
 * Check BenchMark
 */

class BenchMark {

    public $START;
    public $END;
    public $MEMORY;
    public $MEMORY_PEAK;
    public $TIME;
    public $ARR_VAL;

    function __construct() {
        $this->START = $this->the_microtime();
        $this->MEMORY = memory_get_usage();
        $this->MEMORY_PEAK = memory_get_peak_usage();
    }

    private function the_microtime() {
        $time = explode(" ", microtime());
        return $time[0] + $time[0];
    }

    private function mem_in($para) {
        return substr($para / 1024, 0, 6) . 'K';
    }

    public function BM_GET() {

        $this->TIME = substr(($this->the_microtime() - $this->START), 0, 5);
        $this->MEMORY = $this->mem_in(memory_get_usage() - $this->MEMORY) . ' | ' . $this->mem_in(memory_get_peak_usage() - $this->MEMORY_PEAK);

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