<?php
$date = date('Y-m-d',strtotime('2023-05-22'));
$activePage = 1;
if (isset($_GET['date'])) {
    $get = $_GET;
    $date = $get['date'];
    $activePage = isset($get['page']) ? $get['page'] : 1;
}

$epaper = $con->query("SELECT * FROM epapers WHERE date = '$date'");
if ($epaper->num_rows) {
    $row = (object) $epaper->fetch_assoc();
    $epaper_id = $row->id;
    $pdf = $row->path;
} else {
    return false;
    die('noting found');
}

$image = $con->query("SELECT * from epaper_images where epaper_id = '$epaper_id' and page = '$activePage'");
if ($image->num_rows) {
    $image = (object) $image->fetch_assoc();
    $image = $image->image;
} else {
    return false;
    die('nothing found');
}