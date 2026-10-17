<?php 

require __DIR__ . '/../vendor/autoload.php';

// serveFile.php
if (isset($_GET['file'])) {
    if (!preg_match('#^[a-f0-9]{64}/[a-zA-Z0-9._\-]+$#', $_GET['file'])) {
        http_response_code(400);
        echo 'Bad request.';
        exit;
    }
    if(isset($_GET['pending'])){
        $filePath = __DIR__ . '/../pending/' . $_GET['file'];
    }else{
        $filePath = __DIR__ . '/../storage/' . $_GET['file'];
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