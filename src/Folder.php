<?php


namespace Muller\Filemanager;

use \Muller\Filemanager\Contracts\File;

class Folder extends AbstractFile implements File 
{
    public function __construct($name, $path)
    {
        parent::__construct($name, $path);
        $this->type = 'folder';
    }

    public static function getItems($path) : array
    {
        $items = static::makeItems($path, 'folder');

        return $items;
    }
}