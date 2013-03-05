<?php
$db = new mysqli('localhost','root','','opendata');
require_once 'functions.php';

mysql_connect('localhost', 'root', '');
mysql_select_db('opendata');

$main_categories_id = array(
    'printers' => 1,
    'mfp' => 2,
    'plotters' => 3,
    'RC' => 4,
    'inks' => 5
);
$allow_formats = array('A0', 'A1', 'A2');

$file_data_lines = file('import.csv');
$product_data = array();

foreach ($file_data_lines as $num_line => $line) {
    if ($num_line < 6) {
        continue;
    }
    $data_line = str_getcsv($line, ';');
    $data_line = array_map('trim', $data_line);

    /*    if((int)$data_line[122] == 120 OR (int)$data_line[122] == 100 OR (int)$data_line[122] == 250){
            die($num_line.' '.$line);
        }else{
            continue;
        }*/

    $category['mfp_flag'] = (int)(isset($data_line[102])) ? : 0;
    $category['printer_flag'] = (bool)in_array($data_line[117], $allow_formats);


    $sphere_of_use = array();
    $sort_sphere_of_use = array('home' => 'home', 'office' => 'office', 'photos', 'professional');
    $sphere_of_use_dump = array($data_line[98], $data_line[99], $data_line[100], $data_line[101]);
    foreach ($sphere_of_use_dump as $val) {
        $val = trim(strtolower($val));
        if (in_array($val, $sort_sphere_of_use)) {
            $sphere_of_use[] = $val;
        }
    }

    $functions = array('print');
    $functions_dump = array('scan' => $data_line[102], 'copy' => $data_line[105], 'fax' => $data_line[106]);
    foreach ($functions_dump as $key => $val) {
        $val = (int)$val;
        if ($val) {
            $functions[] = $key;
        }
    }

    $functions = implode(', ', $functions);

    $sheet_feed_data = array('None','Flatbed','Sheetfed');
    $scanner_type_data = array('None','Tablet','Sheetfed');
    // key is a attribute_id
    $technical_info = array(
        7 => array(
            12 => $data_line[94],
            13 => $data_line[95],
            14 => $data_line[96],
            15 => implode('/', array_unique($sphere_of_use)),
            16 => $data_line[101],
            17 => $functions
        ),
        8 => array( //print
            18 => 0,//'Printing technology',//none
            26 => (int)$data_line[110],
            19 => (int)(explode(' ',$data_line[120])[0]),
            20 => $data_line[124],
            21 => (float)$data_line[118],
            22 => (float)$data_line[119],
            23 => 0,//'Print head',
            24 => 0,//'Colours'//none
        ),
        9 => array( //Media handling  + +
            25 => $data_line[117],
            27 => $data_line[147],
            28 => (int)$data_line[103],//'Automatic Document Feed',
            29 => $sheet_feed_data[(int)$data_line[123]],//'Document Feed',//123
            30 => (int)$data_line[126],
            31 => (int)$data_line[127],
            32 => (int)$data_line[128],
            33 => 0,//'Print Textile Media',//none
            34 => (int)$data_line[130],
            35 => (int)$data_line[129],
            36 => 0,//'Compatible Media Thickness, min',//none
            37 => 0,//'Compatible Media Thickness, max'//none
        ),
        10 => array( //A-i-O
            38 => $scanner_type_data[(int)$data_line[122]],//'Scanner type',//123 0-nonoe, 1- tablet, 2-Sheetfed
            39 => 0,//'Scan speed black',//none стр/мин
            40 => 0,//'Scan speed colour',//none стр/мин
            41 => $data_line[150],//'Scanning Resolution',//151
            42 => (int)$data_line[104],//'Two-side scanner',//105
            43 => 0,//'Auto Scan Sheet Feeder',//none
            44 => 0,//'Copy spead',//none
            45 => (int)$data_line[104],//'Fax'//107
        ),
        11 => array( //Add..
            46 => (int)$data_line[111],//'Interfaces',//ethernet 112
            47 => (int)$data_line[112],//'Wi-fi',
            48 => (int)$data_line[115],//'Clouds',
            49 => (int)$data_line[107],//'Duplex',
            50 => (int)$data_line[108],//'Does not require the connection to the computer',//109
            51 => (int)$data_line[109],//'Memor card',
            52 => $data_line[131]
        ),
        12 => array( //INK
            53 => (boolean)$data_line[132],//'dye-based ink',
            54 => 1,//'pigment ink',
            55 => 0,//'pigment ultra ink',
            56 => 0,//'invisible ink',
            57 => 0,//'sublimation ink',
            58 => 0,//'ecosolvent ink',

        ),
        13 => array( //Other
            59 => $data_line[144],//'Product dimensions  (Width x Depth x Height)',
            60 => str_replace(',','.',explode(' ',$data_line[125])[0]),
            61 => $data_line[143],
            62 => explode(' ',$data_line[147])[0],
            63 => 0,//'Power Supply',//none
            64 => $data_line[151],//'operating voltage',
            65 => $data_line[152],//'AC frequency',
            66 => explode(' ',$data_line[148])[0]));//'Warranty'

    /*&$ids = array(
        'ciss'=> $data_line[27],
        'print_ciss'=> $data_line[37],
        'print_rc'=> $data_line[48],
        'cartridge'=> $data_line[66],
        'ink_set'=> $data_line[82]
    );*/

    if (count($ids)) {
        $product_data = array(
            'line' => (int)$num_line,
            'ids' => $ids,
            'technical_info' => $technical_info,
            'producer' => $data_line[94],
            'series' => $data_line[95],
            'model' => $data_line[96],
            'category' => $category
        );
        saveData($product_data);
    }
    $temp = 0;
}

die('ds');