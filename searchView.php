<?php
/**
 * Description of SearchView
 */
class SearchView extends View {
    public function __construct() {
        //echo "SearchView";
        $this->name = "Wyszukiwarka"; 
    }
    
    function contentView() {
        ?>
        <div class="searchView">   
            <div id="searchForm">
               <form method='GET' action='<?php $_SERVER['PHP_SELF']?>'>
               <label>Specjalizacja </label>
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
               
               
               <label>Miasto</label>
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
               <input type='submit' value='szukaj' name='szukaj'>
               </form>
            </div>
        </div>
        <?php
    }
}
