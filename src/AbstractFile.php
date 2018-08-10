<?php


namespace Muller\Filemanager;

use \Muller\Filemanager\Contracts\File as FileContract;

abstract class AbstractFile implements FileContract
{
    public $name;

    public $path;

    public $type;
    
    public function __construct($name, $path)
    {
        $this->name = $this->getName($name);
        $this->path = $path;
        $this->type = 'file';
    }

    protected function getName($name)
    {
        $name = explode('/', $name);
        return array_pop($name);
    }

    protected static function makeItems($path, $type)
    {
        $items = [];
        $storage = app('filesystem');
        $_items = $type === 'file' ? $storage->files($path) : $storage->directories($path);
        foreach ($_items as $_item) {
            $items[] = new static($_item, $path);
        }

        return $items;
    }
}