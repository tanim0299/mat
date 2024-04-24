<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class CreateInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating Interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        $path = $this->filePath($file);

        $this->createDir($path);

        if(File::exists($path))
        {
            $this->error('File '.$path.' already exists.');
            return;
        }

        $text = '<?php
        namespace App\Interfaces;
        use App\Interfaces\BaseInterface;

        interface '.$file.' extends BaseInterface{

        }
        ';

        File::put($path,$text);

        $this->info('File '.$path.' created');
    }

    public function filePath($file)
    {
        $file = str_replace('.','/',$file).'.php';

        $path = 'app/Interfaces/'.$file;

        return $path;

    }

    public function createDir($path)
    {
        $dir = dirname($path);

        if(!file_exists($dir))
        {
            mkdir($dir,0777,true);
        }
    }
}
