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
    public Logger $logger;
    public array $mimeTypes;
    public string $folder;

    public function __construct()
    {
        $this->storageDir = __DIR__ . '/../storage/';
        $this->logger = LoggerHelper::getLogger();
        $this->mimeTypes = fgetcsv(fopen(__DIR__ . '/allowed_mime.csv', 'r'));
    }

    public function getEmbedContent(string $target, string $expectedFilename = 'default'): array
    {
        $this->folder = $target;

        $files = $this->getFilesStartingWith($target, $expectedFilename);

        if (empty($files)) {
            throw new Exception("No valid default files found.");
        }
        if (count($files) > 1) {
            throw new UnexpectedValueException("More than one default file present, found " . print_r($files, true), 1);
        }

        $filename = array_shift($files);
        return ['mime' => $this->fetchMimeType($target, $filename), 'contents' => $this->fetchFileContent($target, $filename)];
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
        return file_get_contents($filePath);
    }

    private function fetchMimeType(string $target, string $filename): string
    {
        $filePath = $this->storageDir . $target . '/' . $filename;
        return MimeType::fromFile($filePath);
    }
}

?>
