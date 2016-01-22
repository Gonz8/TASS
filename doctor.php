<?php
/**
 * Description of Doctor
 */
class Doctor {
    public $name;
    public $dl_href;
    public $zl_href;
    public $spec;
    public $dl_score;
    public $dl_comments;
    public $zl_comments;
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    public function __get($name) {
        return $this->$name;
    }
}
