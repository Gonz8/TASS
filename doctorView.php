<?php
/**
 * Description of DoctorView
 */
class DoctorView extends View {
    public function __construct() {
        echo "DoctorView";
        $this->name = "Lekarz"; 
    }
    
    public function contentView() {
        echo '<div id="contentView">';
        
        echo '</div>';
    }
}