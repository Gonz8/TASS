<!DOCTYPE html>
<?php
/*
* plik glowny.
*/
require('page.php');
include('simplehtmldom/simple_html_dom.php');
        
//$page = new Page();
//$page->show();

//print 'sdd';
$dl_url = 'http://www.dobrylekarz.info';
$zl_url = 'http://www.znanylekarz.pl';
//$ch = curl_init($dl_url."/miejsce/gdansk?filter0=53&filter2=**ALL**");
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//$content = curl_exec($ch);
//curl_close($ch);
$ch2 = curl_init($zl_url."/dermatolog/gdansk");
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_BINARYTRANSFER, true);
$content2 = curl_exec($ch2);
curl_close($ch2);
//var_dump($content);
//var_dump($content2);

function dl_getRows($url,$pager = FALSE) {
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
        $objs[] = $item;
    } 

    print_r($objs);
    return $last_page;
}
$last_page = dl_getRows($dl_url."/miejsce/gdansk?filter0=53&filter2=**ALL**", TRUE);

for($i = $last_page; $i >= 1; $i--) {
    dl_getRows($dl_url."/miejsce/gdansk?filter0=53&filter2=**ALL**"."&page=".$i);
}

//$dom = new simple_html_dom();
//$dom->load($content);
//$cm1 = $dom->find('div#contentmiddle',0);
//   //echo $cm1->find('div.view-content',0)->innertext;
//    $list = $cm1->find('div.view-content',0);
//
//$pager1 = $cm1->find('div.pager',0);
//$last_page = "";
//foreach($pager1->find('a') as $elem)
//       $last_page = $elem->href;
//$temp = explode('page=', $last_page);
//$temp2 = explode('&', $temp[1]);
//$last_page = $temp2[0];
//
//
//
//
//
//
//foreach($list->find('tr.odd, tr.even') as $elem) {
//    //echo $elem->innertext . '</br>';
//    $name_td    = $elem->find('td',0);
//    $item['name']    = $name_td->find('a', 0)->innertext;
//    $item['href'] = $name_td->find('a', 0)->href;
//    $objs[] = $item;
//} 
//
//print_r($objs);