<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Upload an advertisement</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>
        <form method="post" action="/upload.php" enctype="multipart/form-data">
            <input type="text" name="ad_name" placeholder="Author Name"><br>
            <input type="text" name="ad_link" placeholder="Link to"><br><br>
            <input type="file" name="ad_files[]" multiple><br>
            <input type="submit">
        </form>
    </body>
</html>