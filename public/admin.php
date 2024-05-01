<?php
require __DIR__.'/../src/auth.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>System Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #d3d3d3;
            margin: 0;
            padding: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 8px;
            margin-top: 2px;
        }
        span {
            font-weight: bold;
            color: #0000ff;
        }
    </style>
</head>
<body>
    <span>Cool Admin Panel</span><br>
    <img src="logo.png">
    <h2>Pending Items</h2>
    <ul>
    <?php
    require __DIR__.'/../vendor/autoload.php';
    $pending_dir = __DIR__.'/../pending';
    $folders = array_diff(scandir($pending_dir), ['.', '..']);
    $array = [];
    foreach ($folders as $key => $item) {
        $info = file_get_contents($pending_dir.'/'.$item.'/.info');
        $obj = (object)preg_split("/\r\n|\n|\r/", $info);
        $obj->author = $obj->{"0"}; unset($obj->{"0"});
        $obj->link = $obj->{"1"}; unset($obj->{"1"});
        $obj->name = $item;
        $array[] = $obj;
    }
    if(count($array) == 0){
    echo "<li>Nothing to confirm :D</li>";
    }else{
    foreach ($array as $key => $value) {
    ?> 
    <li>
        Author: <?php echo htmlspecialchars($value->author); ?><br>
        Link: <?php echo htmlspecialchars($value->link); ?><br>
        <a href="/pending.php?i=<?php echo $value->name; ?>" target="_blank">Preview</a><br><br>
        <a href="/choice.php?yes=1&itm=<?php echo $value->name; ?>">They cooked with this one</a>
        <a href="">They cooked but nobody ate</a>
    </li>
    <?php } }?>
    </ul>

    <h2>Storage Items</h2>
    <ul>
    <?php
    $storage_dir = __DIR__.'/../storage';
    $folders = array_diff(scandir($storage_dir), ['.', '..']);
    $array = [];
    foreach ($folders as $key => $item) {
        $info = file_get_contents($storage_dir.'/'.$item.'/.info');
        $obj = (object)preg_split("/\r\n|\n|\r/", $info);
        $obj->author = $obj->{"0"}; unset($obj->{"0"});
        $obj->link = $obj->{"1"}; unset($obj->{"1"});
        $array[] = $obj;
    }
    
    foreach ($array as $key => $value) {
    ?> 
    <li>
        Author: <?php echo htmlspecialchars($value->author); ?><br>
        Link: <?php echo htmlspecialchars($value->link); ?>
    </li>
    <?php } ?>
    </ul>
</body>
</html>
