<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/embed.php';

$embed = new Embed;
use Bayfront\MimeTypes\MimeType;

if (count($_POST) !== 2 || empty($_FILES['ad_files'])) {
    http_response_code(422);
    return "You're missing something.";
}

$name = preg_replace("/[^a-zA-Z0-9_\-]/", "", $_POST['ad_name']);  // Sanitize name
$link = filter_var($_POST['ad_link'], FILTER_SANITIZE_URL);  // Sanitize the URL

if (!preg_match('/^\w+$/', $name)) {
    $embed->logger->info($name . ' tried to do something silly');
    http_response_code(422);
    return "Invalid directory name.";
}

$default_present = false;
foreach ($_FILES['ad_files']['name'] as $value) {
    if (str_starts_with($value, 'default.')) {
        $default_present = true;
        break;
    }
}
if (!$default_present) {
    http_response_code(400);
    return "No default file present.";
}

$uniqueName = hash('sha256', $name . time());
$path = __DIR__ . '/../pending/' . $uniqueName;

if (!is_dir($path) && !mkdir($path, 0777, true)) {
    $embed->logger->error($path . ' failed to create');
    http_response_code(500);
    return "Failed to create directory.";
}

foreach ($_FILES['ad_files']['tmp_name'] as $key => $tmp_name) {
    $error = $_FILES['ad_files']['error'][$key];
    if ($error !== UPLOAD_ERR_OK) {
        http_response_code(422);
        return "File upload error: " . $error;
    }

    $fileDestination = $path . '/' . basename($_FILES['ad_files']['name'][$key]);
    $fileMime = MimeType::fromFile($_FILES['ad_files']['name'][$key]);

    if (!in_array($fileMime, $embed->mimeTypes)) {
        $embed->logger->error($name . ' has forbidden MIME type ' . $fileMime);
        http_response_code(422);
        return "Forbidden MIME type detected.";
    }

    if (!move_uploaded_file($tmp_name, $fileDestination)) {
        http_response_code(500);
        return "Failed to move uploaded file.";
    }
}

file_put_contents($path . '/.info', $name . PHP_EOL . $link);
$embed->logger->info('New Ad Submission named: '.$name);
echo "Upload successful! An Admin will take a look at your Ad and add it soon.";
?>
