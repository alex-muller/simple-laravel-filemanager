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
    }

    protected function getName($name)
    {
        $name = explode('/', $name);
        return array_pop($name);
    }


}
