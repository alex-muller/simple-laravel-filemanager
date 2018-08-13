<?php


namespace Muller\Filemanager;



use \Muller\Filemanager\Contracts\File as FileContract;

class File extends AbstractFile implements FileContract
{
    protected $types = [
        'image' => ['bmp', 'gif', 'ico', 'jpg', 'jpeg', 'png', 'svg', 'webp'],
        'audio' => ['aa', 'aac', 'mp3', 'ogg', 'wav', 'wma'],
        'video' => ['3gp', 'avi', 'flv', 'mkv', 'mpeg', 'mp4', 'mpg', 'webm', 'wmv']
    ];
    public function __construct($name, $path)
    {
        parent::__construct($name, $path);
        $this->type = $this->getFileType($name);
    }

    protected function getFileType($name)
    {
        $type = 'file';
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        foreach ($this->types as $_type => $formats) {
            if (in_array($extension, $formats)) {
                $type = $_type;
                break;
            }
        }

        return $type;
    }

}
