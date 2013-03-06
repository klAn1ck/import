<?php

$db = mysqli_connect('localhost','root','','opencart');
$p = mysqli_query($db,'SET NAMES utf8');
$te = mysqli_query($db,'SELECT `attribute_id`,`text` FROM `oc_product_attribute`');
while($temp = mysqli_fetch_assoc($te)){
    echo $temp['text']."\r\n";
}

//my comment

