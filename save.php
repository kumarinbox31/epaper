<?php
include 'conn.php';
if (isset($_POST)) {

    $timestamp = time();
    $date = $_POST['date'];
    $folder = date('Y-m', strtotime($date));
    $outputFolder = 'media/' . $folder;

    // check epaper avalaible or not in given  date
    $chk = $con->query("SELECT * FROM `epapers` WHERE `date` = '$date'");
    if (!$chk->num_rows) {
        // upload pdf file 
        $pdfFile = $_POST['pdfFile'];
        $tempname = tempnam(sys_get_temp_dir(), $pdfFile);
        $info = pathinfo($pdfFile);
        $ext = $info['extension']; // get the extension of the file
        $newname = "pdf_" . time() . "." . $ext;
        $target = $outputFolder . '/' . $newname;
        $res = copy($tempname, $target);

        // insert pdf file data in epapers table
        $ins = $con->query("INSERT INTO `epapers`(`path`, `date`) VALUES ('$target','$date')");
        $epaper_id = $con->insert_id;

        // upload pdf images
        $data = (array) json_decode($_POST['data']);
        if (count($data)) {
            foreach ($data as $key => $image) {
                $image_path = saveFile($image, $key + 1, $outputFolder);
                $page = $key + 1;
                // insert epaper images
                $con->query("INSERT INTO  `epaper_images` (`epaper_id`,`image`,`page`) VALUES ('$epaper_id', '$image_path', '$page')");
            }
            echo 1;
        }
    } else {
        echo 2;
    }
}
function saveFile($base64Data, $pageNum, $outputFolder)
{
    global $timestamp;
    if (!file_exists($outputFolder)) {
        mkdir($outputFolder);
    }
    // Remove data URI prefix to get the actual base64 image data
    $base64Image = str_replace('data:image/png;base64,', '', $base64Data);

    // Generate a unique filename
    $fileName = "page_{$timestamp}_{$pageNum}.png";

    // Decode the base64 image data and save it to a file
    $imageData = base64_decode($base64Image);
    $image_path = $outputFolder . '/' . $fileName;
    file_put_contents($image_path, $imageData);
    return $image_path;
}