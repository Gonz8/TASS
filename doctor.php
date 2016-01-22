<?php
/**
 * Description of Doctor
 */
class Doctor {
    public $name;
    public $dl_href;
    public $zl_href;
    public $spec;
    public $zl_score;
    public $zl_comments_cnt;
    public $dl_comments_cnt;
    
    public $full_matched;
    public $part_matched;
    
    public function __construct($name) {
        $this->name = $name;
        $this->full_matched = false;
        $this->part_matched = false;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    public function __get($name) {
        return $this->$name;
    }
}
