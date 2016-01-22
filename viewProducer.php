<?php
include 'view.php';
include 'searchView.php';
include 'listView.php';
include 'doctorView.php';
/**
 * Description of ViewProducer
 */
class ViewProducer {
    public function getView($id) {
        $result;
        switch ($id) {
            case "list":
                $result = new ListView();
                break;
            case 1:
                $result = new DoctorView();
                break;
            default:
                $result = new SearchView();
        }
        
        return $result;
    }
}
