<?php
use Katzgrau\KLogger\Logger;
use Psr\Log\LogLevel;

require __DIR__.'/../vendor/autoload.php';

final class LoggerHelper {
    private static $logger = null;

    public static function getLogger() {
        if (self::$logger === null) {
            self::$logger = new Katzgrau\KLogger\Logger(__DIR__.'/../logs',LogLevel::DEBUG,[
                'extension' => 'YUGOSLAVIALOG',
                'prefix' => '' //this is meant to be empty
            ]);
        }
        return self::$logger;
    }
}


final class Embed{

    private string $storage_dir;
    protected Logger $logger;

    public string $fallback_target;

    public string $target;

    public string $type;

    public array $mime_types;

    public string $expected_filename;
 
    public function __construct()
    {
        $this->storage_dir = __DIR__.'/../storage/';
        if(!file_exists($this->storage_dir)){
            $this->logger->emergency('<@278164006987104256> STORAGE DIRECTORY IS NOT ACCESSIBLE');
            throw new Exception("FATAL: Storage directory is not accessible", 1);
            die;
        }

        $this->fallback_target = 'fallback';

        $this->expected_filename = 'default';

        //incase it still fucks up we have a fallback
        $this->target = $this->fallback_target;

        $this->mime_types = fgetcsv(fopen(__DIR__.'/allowed_mime.csv','r'));
        //dd($this->storage_dir);

        $this->logger = LoggerHelper::getLogger();
    }

    public function setTarget(string $target){
        if(!file_exists($this->storage_dir.'/'.$target)){
            $this->logger->warning('User requested '.$target.' but it could not be found');
            return;
        }
        $this->target = $target;
    }

    public function checkMime(){
        if($this->target == $this->fallback_target){
            $this->logger->info('Tried getting Fallback mimetype');
        }
    }

    public function getEmbedDefault(){
        $contents = array_diff(scandir($this->storage_dir.$this->target), array('..', '.'));
        $file = array_filter($contents,function($item){
            return str_starts_with($item,$this->expected_filename);
        });
        if (count($file) > 1){
            throw new UnexpectedValueException("More than one default file present, found ".print_r($file,true), 1);
        }
        dd($contents,$file);
    }

}

$embed = new Embed;
$embed->setTarget('fallback');
$embed->getEmbedDefault();
?>