<?php
//include ('user.php');
//include ('object.php');
//include ('connector.php');
include 'doctor.php';


/**
 * Description of View
 *
 */
class View {
    public $name;
    
    public function __construct()
    {
        echo "view construct";
    }
    
    public function contentView() {
        echo "MainView";
     }
    
}
