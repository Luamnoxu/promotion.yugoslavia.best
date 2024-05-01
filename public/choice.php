<?php
require __DIR__ . '/../src/auth.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__.'/../src/embed.php';

$embed = new Embed;
$log = $embed->logger; // Assuming $embed->logger returns an instance of a PSR-3 compliant logger

if (isset($_GET['yes']) && isset($_GET['itm'])) {
    function deleteDirectory($dirPath)
    {
        global $log; // Make $log available inside the function
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $dirPath . '/' . $file;
                    if (is_dir($filePath)) {
                        deleteDirectory($filePath);
                    } else {
                        if (unlink($filePath)) {
                            $log->info("Deleted file: {$filePath}");
                        } else {
                            $log->error("Failed to delete file: {$filePath}");
                        }
                    }
                }
            }
            if (rmdir($dirPath)) {
                $log->info("Deleted directory: {$dirPath}");
            } else {
                $log->error("Failed to delete directory: {$dirPath}");
            }
        }
    }

    $choice = (bool)$_GET['yes'];
    $itm = $_GET['itm'];
    $pending_dir = __DIR__ . '/../pending';
    if ($choice) {
        $storagePath = __DIR__ . "/../storage/" . $itm;
        if (file_exists($pending_dir . '/' . $itm)) {
            if (rename($pending_dir . '/' . $itm, $storagePath)) {
                $log->info("Moved item from pending to storage: {$itm}");
            } else {
                $log->error("Failed to move item from pending to storage: {$itm}");
            }
        } else {
            $log->warning("Item does not exist in pending directory: {$itm}");
        }
        header('Location: '.'admin.php');
    } else {
        $delete = $pending_dir . '/' . $itm;
        deleteDirectory($delete);
        header('Location: '.'admin.php');
    }
}
