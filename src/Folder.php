<?php


namespace Muller\Filemanager;

use Illuminate\Filesystem\FilesystemManager;
use \Muller\Filemanager\Contracts\File;

class Folder extends AbstractFile implements File 
{
    public function __construct($name, $path)
    {
        parent::__construct($name, $path);
        $this->type = 'folder';
    }

}
