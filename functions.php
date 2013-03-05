<?php
function saveData(array $data)
{
    global $db;
    /*$query_data = serialize($data['ids']);
    $query_line = $data['line'];
    mysqli_query($db,"INSERT INTO `gd_products` SET `data`='{$query_data}', `line_number`='{$query_line}'");//die();*/

    $gd_product_data = mysqli_query($db, "SELECT `gd_id` FROM `gd_products` WHERE `line_number`='{$data['line']}'");
    $gd_id = mysqli_fetch_assoc($gd_product_data)['gd_id'];
    //die($gd_id);
    foreach ($data['technical_info'] as $attribute_group_id => $attributes) {
        foreach ($attributes as $attribute_id => $attribute_val) {
            /*mysqli_query($db, "INSERT INTO `oc_product_attribute` SET `product_id`='{$gd_id}',
                            `attribute_id`='{$attribute_id}', `language_id`='1',`text`='{$attribute_val}'");*/
        }
    }

}

