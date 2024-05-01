<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Upload an advertisement</title>
    <meta name="description" content="Submit your advertisement for approval.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        form {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 15px;
            margin-top: 20px;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .instructions {
            margin-bottom: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <img src="logo.png">
    <form method="post" action="/upload.php" enctype="multipart/form-data">
        <div class="instructions">
            <p>Please fill out the form to submit your advertisement. Ensure that your submission includes a default.[filetype]</p>
            <p>Allowed filetypes:</p>
            <ul>
                <?php foreach (fgetcsv(fopen(__DIR__.'/../src/allowed_mime.csv','r')) as $key => $value) {
                    echo "<li>".$value."</li>";
                } ?>
            </ul>
        </div>
        <input type="text" name="ad_name" placeholder="Your Name" required><br>
        <input type="text" name="ad_link" placeholder="Link to" required><br><br>
        <input type="file" name="ad_files[]" multiple required><br><br>
        <span>Upload One or more Files</span><br>
        <input type="submit" value="Upload File">
    </form>
</body>
</html>
