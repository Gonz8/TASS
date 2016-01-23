<?php
//include('user.php');
//include ('object.php');
include 'viewProducer.php';
//include ('alert.php');
require_once('fw_print.php');
require_once('consts.php');


/**
 * Description of Strona
 *
 */
class Page {
    //public $object; //bedzie tabela docRow
    public $view;
    public $title;
    private $keywords = "TASS, Znany Lekarz, Dobry Lekarz, DobryZnanyLekarz";
    private $style = "css/stylesheet.css";
    private $js = "js/page.js";
    
    public function __construct() {
        if(isset($_GET['id'])){
            $id=$_GET['id'];
        } else {
            $id = "search";
        }
        $viewProducer = new ViewProducer();
        $this->view = $viewProducer->getView($id);
        if($this->view->name){
            $this->title = "DobryZnanyLekarz - " . $this->view->name;
        }else {
            $this->title = "DobryZnanyLekarz";
        }
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    public function __get($name) {
        return $this->$name;
    }
    
    
    public function show() 
    {   
        echo "<html>\n<head>\n";
        $this->showTitle();
        $this->showKeywords();
        $this->showStyle();

                $this->createHead();
                if(isset($_GET['id'])){
                    echo '<div class="content">';
                    $this->createContent();
                    echo '</div>';
                } else {
                    $this->createContent();
                }
                
                $this->createFooter(true);
            
        
    }
    
    public function showTitle() {
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"> \n "
                . "<title> $this->title </title>";
    }
    
    public function showKeywords(){
        echo "<meta name=\"keywords\" content=\"". htmlentities($this->keywords) .
            "\" />";
    }
    public function showStyle() {
        
        echo "<link href=\"$this->style\" rel=\"stylesheet\" type=\"text/css\" /> \n ";
        //echo "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>";
        echo "<script type='text/javascript' src='". $this->js ."'></script>";
        echo "</head>";     
    }
    
//    public function showMenu($tabs) {
//        echo '<div class="tabs_container">';
//        echo '<ul>';
//        foreach ($tabs as $name => $url) {
//            $this->createBtn($name, $url, $this->isBtnActive($url));
//        }
//        echo '</ul>';
//        echo '</div>';
//        
//    }
    
    private function createHead() {
        $home_url = 'index.php';
        ?>
        <body>
            <div id="title">
                <div id="logo">
                    <?php
                    echo '<a href="'. $home_url .'">';
                    ?>
                    <img src="img/logo.PNG" alt="logo" width="170" height="90"/>
                    </a>
                        </div>
                        <div id="headerInfo">
                            <?php
                                if(isset($_GET['spec']) && isset($_GET['city'])){
                                    echo Consts::$my_spec[$_GET['spec']].' '.Consts::$my_city[$_GET['city']];
                                }
                            ?>
                        </div>
                        <div id="headerSearch">
                            <?php
                                if(isset($_GET['id']))
                                    $this->headerSearch();
                            ?>
                        </div>
            </div>
<?php
    }
    
    private function headerSearch() {
        ?>
        <form method='GET' action='<?php $_SERVER['PHP_SELF']?>'>
               <SELECT name="spec" required>
               <?php
                    $spec_arr = Consts::$my_spec;
                    asort($spec_arr);
                    printFormSelectOption('', '', 0);
                    foreach($spec_arr as $key => $val){
                        printFormSelectOption($key, $val, 0);
                    }
               ?>
               </SELECT>
               <SELECT name="city" required>
               <?php
                    $city_arr = Consts::$my_city;
                    asort($city_arr);
                    printFormSelectOption('', '', 0);
                    foreach($city_arr as $key => $val){
                        printFormSelectOption($key, $val, 0);
                    }
               ?>
               </SELECT>  
               <input type='hidden' value='list' name='id'>
               <input type='submit' value='ok' name='szukaj'>
               </form>
            <?php
    }


    private function createContent() {
        if(isset($_GET['id'])){
            $id=$_GET['id'];
        } else {
            $id = NULL;
        }
        $this->view->contentView();  
    }
    
    private function createFooter() {
//        echo '<div class="hidden" id="url_id">';
//        echo $_GET['id'];
//        echo '</div>';
//        echo '<div class="hidden" id="page_level">';
//        echo $this->level;
//        echo '</div>';
        ?>
        <div id="footer">
            <p>© 2016 DobryZnanyLekarz | Realizacja . </p>
        </div>
        </body>
        </html> <?php
    }
     
}