<?php
/**
 * Description of DoctorView
 */
class DoctorView extends View {
    public $doctor;//tak samo w listView zrób dla spec city page
    public function __construct() {
        $this->name = "Lekarz";
        
        $this->doctor = new Doctor($_POST['doctor']);
        $this->doctor->zl_href = $_POST['zl_href'];
        $this->doctor->dl_href = $_POST['dl_href'];
        $this->doctor->spec = $_POST['spec'];
        $this->doctor->zl_score = $_POST['zl_score'];
        $this->doctor->dl_name = $_POST['dl_name'];
        $this->doctor->full_matched = $_POST['full'] ? true : false;
        $this->doctor->part_matched = $_POST['part'] ? true : false;
        $this->dl_getComments();
        $this->zl_getAllComments();
    }
    
    public function contentView() {
        echo '<div id="contentView">';
        $this->printHeader();
        $this->printCommentsSection();
        echo '</div>';
    }
    
    public function printHeader() {
        echo '<div id="doctor-top">';
        ?><img src="img/person.PNG" alt="doctor" width="80" height="80"/><?php
        echo "<div><span>".$this->doctor->name."</span></br>".$this->doctor->spec."</div>";
        echo '</div>';
    }
    
    private function printCommentsSection() {
//        $lp = $this->zl_getCommentsLastPage(); var_dump($lp);
        echo '<div id="zl_comments" class="comments">';
        echo '<h3 class="commHead">Opinie (ZnanyLekarz)</h4>';
        if ($this->doctor->zl_comments) {
           foreach($this->doctor->zl_comments as $comment) {
                echo '<div>';
                echo "<strong>".$comment->title."</strong> <i>(".$comment->date.")</i>";   echo '</br>';
                echo '<span style="color:green;">'.$comment->rating."</span>"; echo '</br>';
                echo $comment->content;
                echo '</div>';
           } 
        } else
            echo "BRAK";
        echo '</div>';
        
        echo '<div id="dl_comments" class="comments">';
        echo '<h3 class="commHead">Opinie (DobryLekarz)</h4>';
        if ($this->doctor->dl_comments) {
           foreach($this->doctor->dl_comments as $comment) {
                echo '<div>';
                echo "<strong>".$comment->title."</strong> <i>(".$comment->date.")</i>";   echo '</br>';
                echo $comment->content;
                echo '</div>';
            } 
        } else
            echo "BRAK";
        
        echo '</div>';
    }
    
    private function zl_getAllComments() {
        $last_page = $this->zl_getCommentsLastPage();
        $cs = array();
        for($i = 1; $i <= $last_page; $i++) {
            foreach($this->zl_getComments($i) as $row)
                $cs[] = $row;
        }
        $this->doctor->zl_comments = $cs;
    }
    
    private function zl_getComments($page) {
        if ($page =! "1") 
            $dom = $this->getDOM($this->doctor->zl_href."/".$page);
        else
            $dom = $this->getDOM($this->doctor->zl_href);
 
        $comments_sec = $dom->find('section#profile-opinions',0);
        $comments = $comments_sec->find('ul.list-unstyled',1);
        if ($comments) {
            foreach($comments->children() as $elem) {
                //$title = $elem->find('h3',0)->plaintext;
                $details = $elem->find('div.details',0);
                $left = $details->find('div.pull-left',0);
                $right = $details->find('div.pull-right',0);
                $title = $left->find('span.author',0)->plaintext;
                $date = $left->find('time',0)->plaintext;
                $rating = $right->find('span.text-score',0)->plaintext;
                $content = $elem->find('p[itemprop]',0)->plaintext;
                $c = new Comment(trim($content));
                if($title)
                    $c->title = $title;
                $c->date = $date;
                $c->rating = $rating;

                $objs[] = $c;
            }
            return $objs;
        }
        return array();
    }


    private function zl_getCommentsLastPage() {
        $dom = $this->getDOM($this->doctor->zl_href);
        $pagination = $dom->find('ul.pagination',0);

        $last_page = "";
        if($pagination) {
            foreach($pagination->children() as $elem) {
                $vars[] = $elem->plaintext;
            }
            $last_page = $vars[sizeof($vars)-2];
        } else {
            $last_page = "1";
        }
        return trim($last_page);
    }
    
    private function dl_getComments() {
        $dom = $this->getDOM($this->doctor->dl_href);
        $comments = $dom->find('div#comments',0);
        $objs = array();
        if ($comments) {
            foreach($comments->find('div.odd, div.even') as $elem) {
                $title = $elem->find('h3',0)->plaintext;
                $content = $elem->find('div.content',0)->plaintext;
                $date_span = $elem->find('span.submitted',0);
                $c = new Comment(trim($content));
                if($title)
                    $c->title = $title;
                if($date_span) {
                    $text = $date_span->plaintext;
                    $vars = explode(',', $text);
                    $c->date = $vars[1];
                }
                $objs[] = $c;
            } 
            $this->doctor->dl_comments = $objs;
        }
    }
}
