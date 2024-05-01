<?php 

require __DIR__ . '/../vendor/autoload.php';

// serveFile.php
if (isset($_GET['file'])) {
    if(isset($_GET['pending'])){
        $filePath = '../pending/' . $_GET['file'];
    }else{
        $filePath = '../storage/' . $_GET['file'];
    }
    

    if (file_exists($filePath)) {
        // Optionally check the file type and set the appropriate content type
        header('Content-Type: ' . mime_content_type($filePath));
        readfile($filePath);
        exit;
    }
}
http_response_code(404);
echo 'File not found.';


?>