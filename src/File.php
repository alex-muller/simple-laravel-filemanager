<?php


namespace Muller\Filemanager;



use \Muller\Filemanager\Contracts\File as FileContract;

class File extends AbstractFile implements FileContract
{
    public function __construct($name, $path)
    {
        parent::__construct($name, $path);
        $this->type = 'file';
    }

    public static function getItems($path) : array
    {
        $items = static::makeItems($path, 'file');

        return $items;
    }
}
