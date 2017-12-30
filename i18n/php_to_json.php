<?php
include 'PgnJS.i18n.php';

foreach($messages as $lang => $value) {
    $my_file = $lang.'.json';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    fwrite($handle,"// File generated automatically - DO NOT EDIT!\n");
    fwrite($handle,"// Edit instead file PgnJS.i18n.php, then run 'make i18n'\n");
    fwrite($handle,"\n");
    fwrite($handle,json_encode($value, JSON_PRETTY_PRINT));
    fclose($handle);
}
?>
