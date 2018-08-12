<?php


namespace Muller\Filemanager;


use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Pagination\LengthAwarePaginator;


/**
 * Class FileManager
 * @package Muller\Filemanager
 * @property  FilesystemManager $storage
 */

class FileManager
{
    protected $types = [
        'file' => File::class,
        'folder' => Folder::class
    ];

    protected $storage;

    public function __construct()
    {
        $this->storage = app('filesystem');
    }

    public function getItems($path, $page, $per_page)
    {
        $items = $this->makeItems($path, 'folder');
        $items = array_merge($items, $this->makeItems($path, 'file'));
        $total = count($items);
        $offset = $per_page * ($page - 1);
        $items = array_slice($items, $offset, $per_page);
        $items = new LengthAwarePaginator($items, $total, $per_page, $page, ['path' => $path]);

        return $items;
    }

    public function createFolder($name, $path)
    {        
        $path = $path ? $path . '/' . $name : $name;

        return $this->storage->makeDirectory($path);
    }

    public function delete($items)
    {
        foreach ($items as $item){
            $method = $item['type'] === 'folder' ? 'deleteDirectory' : 'delete';
            $path = $item['path'] . '/' . $item['name'];
            $result = $this->storage->$method($path);
        }
        return $result;
    }

    protected function makeItems($path, $type)
    {
        $items = [];
        $_items = $type === 'file' ? $this->storage->files($path) : $this->storage->directories($path);
        foreach ($_items as $_item) {
            $items[] = new $this->types[$type]($_item, $path);
        }

        return $items;
    }
}
