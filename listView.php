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
       
    }
    
    private function dl_getRows($url,$pager = FALSE) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $dom = new simple_html_dom();
        $dom->load($content);
        $cm1 = $dom->find('div#contentmiddle',0);
        $list = $cm1->find('div.view-content',0);

        $last_page = "";
        if($pager) {
            $pager1 = $cm1->find('div.pager',0);
            foreach($pager1->find('a') as $elem)
            $last_page = $elem->href;
            $temp = explode('page=', $last_page);
            $temp2 = explode('&', $temp[1]);
            $last_page = $temp2[0]; 
        }

        foreach($list->find('tr.odd, tr.even') as $elem) {
            $name_td    = $elem->find('td',0);
            $item['name']    = $name_td->find('a', 0)->innertext;
            $item['href'] = $name_td->find('a', 0)->href;
            $spec_td = $elem->find('td',1);
            $item['spec'] = $spec_td->innertext;
            $item['comment_count'] = $elem->find('td',2)->innertext;

            $objs[] = $item;
        } 

        //print_r($objs);
        return $last_page;
    }
    
    private function zl_getRows($url,$pager = FALSE) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $dom = new simple_html_dom();
        $dom->load($content);
        $search_container = $dom->find('div#search-listing-container',0);
        $list = $search_container->find('ul.search-list',0);

        $last_page = "";
    //    if($pager) {
    //        $pager1 = $cm1->find('div.pager',0);
    //        foreach($pager1->find('a') as $elem)
    //        $last_page = $elem->href;
    //        $temp = explode('page=', $last_page);
    //        $temp2 = explode('&', $temp[1]);
    //        $last_page = $temp2[0]; 
    //    }

        foreach($list->children() as $elem) {
            $li_content = $elem->find('div.rank-element-left .content',0);
            $name_sec    = $li_content->find('h4.rank-element-name',0);
            $item['name']    = $name_sec->find('a', 0)->innertext;
            $item['href'] = $name_sec->find('a', 0)->href;
            $rating = $name_sec->find('meta[itemprop=ratingValue]',0);
            $reviews = $name_sec->find('meta[itemprop=reviewCount]',0);
            $item['score'] = $rating ? $rating->content : 'brak';
            $item['comment_count'] = $reviews ? $reviews->content : '0';

            $spec_sec = $li_content->find('h4.rank-element-specializations',0);
            $item['spec'] = $spec_sec->plaintext;
            
            $objs[] = $item;
        } 

    //    print_r($objs);
    //    return $last_page;
    }
}
