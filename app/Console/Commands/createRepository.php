<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class createRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating Repository';

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
        namespace App\Repositories;

        class '.$file.' implements {
            public function index($datatable)
            {

            }

            public function create()
            {

            }

            public function store($data){

            }

            public function show($id){

            }

            public function properties($id){

            }

            public function edit($id){

            }

            public function update($data, $id){

            }

            public function destroy($id){

            }

            public function trash_list($datatable){

            }

            public function restore($id){

            }

            public function delete($id){

            }

            public function print(){

            }
        }
        ';

        File::put($path,$text);

        $this->info('File '.$path.' created');
    }

    public function filePath($file)
    {
        $file = str_replace('.','/',$file).'.php';

        $path = 'app/Repositories/'.$file;

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
