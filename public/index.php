<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <link href="https://fonts.googleapis.com/css2?family=Metal+Mania&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url("assets/ltg.jpg");
            background-color: black;
        }

        #mainContent {
            font-family: "Metal Mania", system-ui;
            font-weight: 400;
            font-size: 30px;
            font-style: normal;
            margin: auto;
            width: 50%;
            border: 10px ridge #f02929;
            padding: 10px;
            background-image: url("assets/skulls.jpg");
            background-color: #fbd155;
        }

        #ourImage {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80%;
        }

        .link {
            margin: auto auto 10px;
            padding: 10px;
            width: 50%;
            border: 3px ridge blue;
            padding: 10px;
            background-image: url("assets/plate.jpg");
            background-color: black;
            text-align: center;
        }

        a {
            color: #18d9f9;
        }
    </style>
</head>

<body>
    <div id=mainContent>
        <img id=ourImage src="assets/logo.png"><br>
        <div class=link id=submitLink><a href="/submit.php">Submit an ad</a><br></div>
        <div class=link id=viewLink><a href="/embed.php">Look at an ad</a></div>
        <div style="background-color: white;">
        EMBED AN AD:<br>
            <code>
                
                &lt;style&gt;
                .yugopromotion {
                    aspect-ratio: 0.123609394313967861557478368356;
                    object-fit: contain;
                    width: 50%;
                }
            &lt;/style&gt;
            &lt;iframe src=&quot;https://promotion.yugoslavia.best/embed.php&quot; class=&quot;yugopromotion&quot; width=&quot;1618&quot; height=&quot;200&quot;&gt;&lt;/iframe&gt;
            </code>
        </div>
    </div>
</body>
</html>