<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__.'/../src/embed.php';

if(!isset($_GET['id'])){
    echo "go away";
}

$id = $_GET['id'];

$embed = new Embed;

$storage = $embed->storageDir;
$storage = array_diff(scandir($storage), ['.', '..']);

if (in_array($id,$storage)){
    require __DIR__.'/../src/status_accepted.php';
    return;
};

$storage = __DIR__.'/../pending';
$storage = array_diff(scandir($storage), ['.', '..']);

if (in_array($id,$storage)){
    require __DIR__.'/../src/status_pending.php';
    return;
};

require __DIR__.'/../src/status_rejected.php';
return;

?>