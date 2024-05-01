<?php 

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/embed.php';

$embed = new Embed;

// Scan and pick a random option
$options = array_diff(scandir($embed->storageDir), array('..', '.', 'fallback'));
$out = $embed->getEmbedContent($options[array_rand($options)]);

// Set the appropriate Content-Type
//header('Content-Type: ' . $out['mime']);

// Define base path for image transformation
$baseImagePath = 'serveFile.php?file=';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Yugoslavia Ads</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            html, body {
                width: 1618px;
                height: 200px;
                background-color: black;
                overflow: hidden;
                box-sizing: border-box;
                padding: 0;
                margin: 0;
                position: relative;
            }
            img {
                width: 100%; /* Make images fill the container */
                height: auto; /* Maintain aspect ratio */
                max-height: 200px; /* Restrict height to 200px */
            }
        </style>
    </head>
    <body>
        <?php
            if (str_starts_with($out['mime'], 'text')) {
                $dom = new DOMDocument();
                @$dom->loadHTML($out['contents']);

                foreach ($dom->getElementsByTagName('img') as $img) {
                    $originalSrc = $img->getAttribute('src');
                    $originalSrc = str_replace('/','',$originalSrc);
                    // Modify the src attribute
                    error_log('Changing img src');
                    $img->setAttribute('src', $baseImagePath . urlencode($embed->folder).'/' . urlencode($originalSrc));
                }

                $filteredHTML = $dom->saveHTML();

                // Use HTMLPurifier to clean the HTML
                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $clean_html = $purifier->purify($filteredHTML);

                echo $clean_html;
            } else {
                // Handle binary data like images
                echo '<img src="data:' . $out['mime'] . ';base64,' . base64_encode($out['contents']) . '" />';
            }
        ?>
        <div style="position: absolute; bottom: 0; right: 0; border: solid 1px black; background-color:white;z-index:999999;color:white;">
        <a href="https://promotion.yugoslavia.best" target="_blank">
            <span>Yugoslavia Promotions</span>
        </a>
        </div>
    </body>
</html>
