<?php
/*$db = new mysqli('localhost','root','','opendata');

$db->query();*/

if($_POST){
    print_r($_FILES);
    print_r($_POST);die();
}

echo '<form method="post">
<input type="file" name="lalala">
<input type="submit" value="ds">
</form>';