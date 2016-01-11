<!DOCTYPE html>
<?php
/*
* plik glowny.
*/
require('page.php');
include('simplehtmldom/simple_html_dom.php');
        
$page = new Page();
$page->show();

//print 'sdd';
//$ch = curl_init("http://www.dobrylekarz.info/miejsce/gdansk?filter0=53&filter2=**ALL**");
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//$content = curl_exec($ch);
//curl_close($ch);
//$ch2 = curl_init("http://www.znanylekarz.pl/dermatolog/gdansk");
//curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch2, CURLOPT_BINARYTRANSFER, true);
//$content2 = curl_exec($ch2);
//curl_close($ch2);
////var_dump($content);
////var_dump($content2);
//
//$dom = new simple_html_dom();
//$dom->load($content);
//foreach($dom->find('div#contentmiddle') as $cnt){
////    foreach($cnt->find('div.view-content') as $e) {
////        echo $e->innertext;
////    }
//    echo $cnt->find('div.view-content',0)->innertext;
//}

    