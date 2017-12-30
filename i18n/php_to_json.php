<?php
include 'PgnJS.i18n.php';

foreach($messages as $lang => $value) {
    $my_file = $lang.'.json';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    fwrite($handle,json_encode($value, JSON_PRETTY_PRINT));
    fclose($handle);
}
?>
