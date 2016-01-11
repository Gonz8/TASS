<?php
//include('user.php');
//include ('object.php');
include ('view.php');
//include ('alert.php');
//require_once('fw.php');


/**
 * Description of Strona
 *
 */
class Page {
    //public $object; //bedzie tabela docRow
    public $view;
    public $title;
    private $keywords = "TASS";
    private $style = "css/stylesheet.css";
    private $js = "js/page.js";
    
    public function __construct() {
        //$this->view = new View($_GET['id']);
        $this->view = new View();
        // dodaj viewProducer --> zwracac bedzie konkretny widok dziedziczacy po View
        if($this->view->name){
            $this->title = $this->view->name . " (TASS)";
        }else {
            $this->title = "TASS";
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
                echo '<div class="content">';
                $this->createContent();
                echo '</div>';
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
                    <img src="img/logo.PNG" width="200" height="120"/>
                    </a>
                        </div>
                        <div id="headerInfo">
                            <?php
                            //print info z wyszukiwania
                            //printHeaderInfos($this->level,$this->user->object,$this->user->client);
                            ?>
                        </div>
                            <?php
                            //ewentualnie div dla wyszukiwarki (form)
                            ?>
            </div>
<?php
    }
    
    private function createContent() {
        if(isset($_GET['id'])){
            $id=$_GET['id'];
        } else {
            $id = NULL;
        }
        echo '<div id="contentView">';
        $this->view->contentView();  
        echo '</div>';
    }
    
    private function createFooter() {
        echo '<div id="footer">';
//        echo '<div class="hidden" id="url_id">';
//        echo $_GET['id'];
//        echo '</div>';
//        echo '<div class="hidden" id="page_level">';
//        echo $this->level;
//        echo '</div>';
        ?>
            <p>© 2016 DobryZnanyLekarz | Realizacja . </p>
        </div>
        </body>
        </html> <?php
    }
     
}