<?php
include 'doctor.php';
include 'comment.php';


/**
 * Description of View
 *
 */
class View {
    public $name;
    
    public function __construct()
    {
    }
    
    public function contentView() {
        echo "MainView";
     }
     
     protected function getDOM($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $dom = new simple_html_dom();
        $dom->load($content);
        return $dom;
    }
    
}
