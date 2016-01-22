<?php

/* 
Funkcje służące do drukowania treści html
 */

function page_redirect($location = NULL)
 {
   if($location){
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
   } 
   else {
        echo '<META HTTP-EQUIV="Refresh" Content="0">'; 
   } 
   exit; 
 }
 
 function printFormInput($type,$name,$value,$desc,$checked) {
     if($checked){
         echo '<INPUT type="'.$type.'" name="'.$name.'" value="'.$value.'" checked>'.$desc.'<br/>';
     }else{
         echo '<INPUT type="'.$type.'" name="'.$name.'" value="'.$value.'">'.$desc.'<br/>';
     }
 }
 
  function printFormSelectOption($value,$desc,$selected) {
     if($selected){
         echo '<OPTION value="'.$value.'" selected>'.$desc.'</OPTION>';
     }else{
         echo '<OPTION value="'.$value.'">'.$desc.'</OPTION>';
     }
 }
 