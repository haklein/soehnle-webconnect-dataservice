<?php 
header('Content-Length: '. 56);

function wh_log($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename))
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}

$data_string = $_GET['data'];

$data_id = hexdec(substr($data_string, 0,2));
$mac = substr($data_string, 2, 12);
$id1 = hexdec(substr($data_string, 14, 6));
$id2 = hexdec(substr($data_string, 20, 6));
$id_str = str_pad($id1, 8, '0', STR_PAD_LEFT) . "-" . str_pad($id2, 8, '0', STR_PAD_LEFT);
$weight = hexdec(substr($data_string, 38, 4))/100;

if ($weight > 0) {
    wh_log(date(DATE_RFC822) . " " . $mac . "_" . $id_str . " weight: " . $weight);
}

# just return what we've sniffed with tcpdump, and taken from https://github.com/biggaeh/bathroom_scales/blob/master/dataservice
switch($data_id) {
    case 0x24:
        echo 'A00000000000000001000000000000000000000000000000bec650a1'; 
        break;
    case 0x22:
        echo 'A20000000000000000000000000000000000000000000000c9950d3f';
        break;
    case 0x25:
        echo 'A00000000000000001000000000000000000000000000000bec650a1';
        break;
    case 0x28:
        echo 'A5000000000000000100000000000000000000000000000056e5abd9';
        break;
    case 0x21:
        echo 'A10000000000000009c4914c0000000000000000000000001d095ec4';
        break;
}
?>
