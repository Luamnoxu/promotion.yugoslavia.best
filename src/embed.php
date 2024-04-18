<?php

use Katzgrau\KLogger\Logger;
use Psr\Log\LogLevel;
use Bayfront\MimeTypes\MimeType;

require __DIR__ . '/../vendor/autoload.php';

final class LoggerHelper
{
    private static $logger = null;

    public static function getLogger()
    {
        if (self::$logger === null) {
            self::$logger = new Katzgrau\KLogger\Logger(__DIR__ . '/../logs', LogLevel::DEBUG, [
                'extension' => 'YUGOSLAVIALOG',
                'prefix' => '' //this is meant to be empty
            ]);
        }
        return self::$logger;
    }
}


final class Embed
{

    public string $storageDir;
    private Logger $logger;
    private array $mimeTypes;
    public string $folder;

    public function __construct()
    {
        $this->storageDir = __DIR__ . '/../storage/';
        $this->logger = LoggerHelper::getLogger();
        $this->checkStorageAccess();
        $this->mimeTypes = fgetcsv(fopen(__DIR__ . '/allowed_mime.csv', 'r'));
    }

    private function checkStorageAccess()
    {
        if (!file_exists($this->storageDir)) {
            $this->logger->emergency('<@278164006987104256> STORAGE DIRECTORY IS NOT ACCESSIBLE');
            throw new Exception("FATAL: Storage directory is not accessible", 1);
        }
    }

    public function getEmbedContent(string $target, string $expectedFilename = 'default'): array
    {
        if (!$this->isTargetValid($target)) {
            $old = $target;
            $target = 'fallback';  // Fallback target if not valid
            $this->logger->warning('Fallback target used as '.$old.' could not be found.');
        }

        $this->folder = $target;

        $files = $this->getFilesStartingWith($target, $expectedFilename);

        if (count($files) > 1) {
            throw new UnexpectedValueException("More than one default file present, found " . print_r($files, true), 1);
        } elseif (empty($files)) {
            throw new Exception("No valid default files found.");
        }

        $filename = array_shift($files);
        return ['mime' => $this->fetchMimeType($target, $filename), 'contents' => $this->fetchFileContent($target, $filename)];
    }

    private function isTargetValid(string $target): bool
    {
        return file_exists($this->storageDir . '/' . $target);
    }

    private function getFilesStartingWith(string $target, string $filename): array
    {
        $contents = array_diff(scandir($this->storageDir . $target), array('..', '.'));
        return array_filter($contents, function ($item) use ($filename) {
            return str_starts_with($item, $filename);
        });
    }

    private function fetchFileContent(string $target, string $filename): string
    {
        $filePath = $this->storageDir . $target . '/' . $filename;

        if(!$this->mimeAllowed($filePath)){
            $this->logger->error($target.' has FORBIDDEN Mime type '.$this->fetchMimeType($target, $filename));
        }

        return file_get_contents($filePath);
    }

    private function mimeAllowed(string $filePath): bool{
        return array_search(MimeType::fromFile($filePath),$this->mimeTypes);
    }

    private function fetchMimeType(string $target, string $filename): string
    {
        $filePath = $this->storageDir . $target . '/' . $filename;
        $fileMime = MimeType::fromFile($filePath);
        return $fileMime;
    }
}
$embed = new Embed;

$options = array_diff(scandir($embed->storageDir), array('..', '.','fallback'));
$out = $embed->getEmbedContent($options[array_rand($options)]);
header('Content-Type: ' . $out['mime']);
//echo ($out['mime']);
if (str_starts_with($out['mime'], 'text')) {
    $dom = new DOMDocument();
    @$dom->loadHTML($out['contents']);

    $baseImagePath = 'serveFile.php?file=';
    foreach ($dom->getElementsByTagName('img') as $img) {
        $originalSrc = $img->getAttribute('src');
        $originalSrc = str_replace('/','',$originalSrc);
        // Modify the src attribute
        error_log('Changing img src');
        $img->setAttribute('src', $baseImagePath . urlencode($embed->folder).'/' . urlencode($originalSrc));
    }
    $filteredHTML = $dom->saveHTML();

    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($filteredHTML);

    echo $clean_html;
}else{
    echo $out['contents'];
}


