<?php
/**
 * Description of ListView
 */
class ListView extends View {
    public function __construct() {
        //echo "ListView";
        $this->name = "Lista lekarzy"; 
    }
    
    public function contentView() {
        $spec = "";
        $city = "";
        $page = "";
        if(isset($_GET['spec'])){
            $spec = $_GET['spec'];
        }
        if(isset($_GET['city'])){
            $city = $_GET['city'];
        }
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
        echo '<div id="contentView">';
        $this->printList($spec, $city, $page);
        echo '</div>';
    }
    
    private function printList($spec, $city, $page) {
        $doctors = $this->getData($spec, $city, $page);
        echo '<div id="docListWrapper">';
        echo $this->zl_getPaginationHTML($spec, $city, $page);
        echo '<table class="docList">';
        foreach ($doctors as $doctor) {
            if($doctor->full_matched)
                echo '<tr class="full">';
            else if($doctor->part_matched)
                echo '<tr class="part">';
            else
                echo '<tr>';

            echo '<td>';
            ?>
            <form method='post' name='podglad' action='?id=doctor&name=<?php echo $doctor->name;?>'>
                <input type='hidden' name ='zl_href' value='<?php echo $doctor->zl_href;?>'>
                <input type='hidden' name ='dl_href' value='<?php echo $doctor->dl_href;?>'>
                <input type='hidden' name ='spec' value='<?php echo $doctor->spec;?>'>
                <input type='hidden' name ='zl_score' value='<?php echo $doctor->zl_score;?>'>
                <input type='submit' name='doctor' value='<?php echo $doctor->name;?>'>
            </form>
            <?php
            echo '</td>';
            echo '<td>' . $doctor->spec . '</td>';          
            echo '<td>';
                echo '<div>';
                echo '<i>ZnanyLekarz</i></br>Ocena: '.$doctor->zl_score.'/5 </br>';
                echo 'Opinii: ' . $doctor->zl_comments_cnt;
                echo '</div>';
            echo '</td>';
            echo '<td>';
                echo '<div>';
                echo '<i>DobryLekarz</i></br>Opinii: ';
                echo $doctor->dl_comments_cnt ? $doctor->dl_comments_cnt : 0;
                echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }
    
    private function getData($spec, $city, $page) {
        $zl_url = Consts::$zl_url;
        $zl_url .= "/".Consts::$zl_spec[$spec]."/".Consts::$zl_city[$city];
        if($page) {
            $zl_url .= "/" . $page;
        }
        $dl_url = Consts::$dl_url;
        $dl_url .= "/miejsce/".Consts::$dl_city[$city]."?filter0=".Consts::$dl_spec[$spec];
        
        $zl_rows = $this->zl_getRows($zl_url);

        $last_page = $this->dl_getLastPage($dl_url);

        for($i = $last_page; $i >= 0; $i--) {
            foreach($this->dl_getRows($dl_url."&page=".$i) as $row)
                $dl_rows[] = $row;
        }
        $this->matchRows($zl_rows, $dl_rows);
        
        return $zl_rows;
    }
    
    private function matchRows($zl_rows, $dl_rows) {
        foreach ($zl_rows as $zl_doc) {
            $names = explode(' ', $zl_doc->name);
            $f_name = $names[0];
            $surname = end($names);
            
            foreach ($dl_rows as $dl_doc) {
                if (strpos($dl_doc['name'], $surname) !== FALSE) {
                    $zl_doc->part_matched = true;
                    $zl_doc->dl_href = Consts::$dl_url.$dl_doc['href'];
                    $zl_doc->dl_comments_cnt = $dl_doc['comment_count'];                    
                    if (strpos($dl_doc['name'], $f_name) !== FALSE) {
                        $zl_doc->full_matched = true;
                    }
                }
            }
        }
    }
    
    private function getDOM($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $dom = new simple_html_dom();
        $dom->load($content);
        return $dom;
    }
    
    private function zl_getRows($url) {
        $dom = $this->getDOM($url);
        $search_container = $dom->find('div#search-listing-container',0);
        $list = $search_container->find('ul.search-list',0);

        foreach($list->children() as $elem) {
            $li_content = $elem->find('div.rank-element-left .content',0);
            $name_sec    = $li_content->find('h4.rank-element-name',0);
            $rating = $name_sec->find('meta[itemprop=ratingValue]',0);
            $reviews = $name_sec->find('meta[itemprop=reviewCount]',0);
            $spec_sec = $li_content->find('h4.rank-element-specializations',0);
            
            $doc_name = $name_sec->find('a', 0)->innertext;
            $doctor = new Doctor($doc_name);
            $doctor->zl_href = $name_sec->find('a', 0)->href;
            $doctor->spec = $spec_sec->plaintext;
            $doctor->zl_comments_cnt = $reviews ? $reviews->content : '0';
            $doctor->zl_score = $rating ? $rating->content : 'brak';
            
            $objs[] = $doctor;
        } 
        return $objs;
    }
    
    private function zl_getPaginationHTML($spec, $city, $page) {
        $url = Consts::$zl_url;
        $url .= "/".Consts::$zl_spec[$spec]."/".Consts::$zl_city[$city];
        if($page) {
            $url .= "/" . $page;
        }
        $dom = $this->getDOM($url);
        $search_container = $dom->find('div#search-listing-container',0);
        $pagination = $search_container->find('ul.pagination',0);
        
        if($pagination) {
            foreach($pagination->children() as $elem) {
                $a = $elem->find('a', 0);
                $link = $a->href;
                $vars = explode("/", $link);
                $page = end($vars);
                if($page != Consts::$zl_city[$city]){
                    $a->href = "index.php?id=list&spec=".$spec."&city=".$city."&page=".$page;
                } else {
                    $a->href = "index.php?id=list&spec=".$spec."&city=".$city;
                }
                if($elem->class && $elem->class == 'prev') {
                    $a->innertext = "Poprzednia";
                }
                else if($elem->class && $elem->class == 'next') {
                    $a->innertext = "Następna";
                }
            } 
            return $pagination->outertext;
        } else {
            return "";
        }
        
    }
    
    private function dl_getRows($url) {
        $dom = $this->getDOM($url);
        $cm = $dom->find('div#contentmiddle',0);
        $list = $cm->find('div.view-content',0);

        foreach($list->find('tr.odd, tr.even') as $elem) {
            $name_td    = $elem->find('td',0);
            $item['name']    = $name_td->find('a', 0)->innertext;
            $item['href'] = $name_td->find('a', 0)->href;
            $item['spec'] = $elem->find('td',1)->innertext;
            $item['comment_count'] = $elem->find('td',2)->innertext;

            $objs[] = $item;
        } 
        return $objs;
    }
    
    private function dl_getLastPage($url) {
        $dom = $this->getDOM($url);
        $cm = $dom->find('div#contentmiddle',0);

        $last_page = "";
        $pager = $cm->find('div.pager',0);
        if($pager) {
            foreach($pager->find('a') as $elem)
                $last_page = $elem->href;
            $temp = explode('page=', $last_page);
            $temp2 = explode('&', $temp[1]);
            $last_page = $temp2[0]; 
        } else {
            $last_page = "0";
        }
        return $last_page;
    }
    
}
