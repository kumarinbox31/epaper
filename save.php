<?php
include 'conn.php';
$timestamp = time();
$date = $_POST['date'];
$folder = date('Y-m',strtotime($date));
$outputFolder = 'media/'.$folder;

// upload pdf file 
$pdfFile = $_POST['pdfFile'];
$info = pathinfo($pdfFile);
$ext = $info['extension']; // get the extension of the file
$newname = "pdf_".time().".".$ext; 
$target = $outputFolder.'/'.$newname;
$res = move_uploaded_file( tempnam(sys_get_temp_dir(),$pdfFile), $target);
if(!$res){
    die($target);
}
print_r($pdfFile);exit;



// upload pdf images
$data = (array)json_decode($_POST['data']);
if(count($data)){
    foreach($data as $key => $image){
        saveFile($image,$key+1,$outputFolder);
        
    }
}

function saveFile($base64Data,$pageNum,$outputFolder){
    global $timestamp;
    if(!file_exists($outputFolder)){
        mkdir($outputFolder);
    }
    // Remove data URI prefix to get the actual base64 image data
    $base64Image = str_replace('data:image/png;base64,', '', $base64Data);

    // Generate a unique filename
    $fileName = "page_{$timestamp}_{$pageNum}.png";

    // Decode the base64 image data and save it to a file
    $imageData = base64_decode($base64Image);
    file_put_contents($outputFolder . '/' . $fileName, $imageData);
}  

