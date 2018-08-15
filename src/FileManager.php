<?php


namespace Muller\Filemanager;


use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Muller\Filemanager\Exceptions\FileManagerException;
use Illuminate\Http\Response;


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
        $this->storage = $this->getStorage();
    }

    public function getItems($path, $page, $search, $per_page)
    {
        $items = $this->makeItems($path, $search, 'folder');
        $items = array_merge($items, $this->makeItems($path, $search, 'file'));
        $total = count($items);
        $offset = $per_page * ($page - 1);
        $items = array_slice($items, $offset, $per_page);
        $items = new LengthAwarePaginator($items, $total, $per_page, $page, ['path' => $path]);

        return $items;
    }

    public function createFolder($name, $path)
    {        
        $path = $path ? $path . '/' . $name : $name;

        if($this->storage->exists($path)) {
            throw new FileManagerException('Folder exists');
        }

        $result = $this->storage->makeDirectory($path);
        return $result;
    }


    public function upload(array $files, $path = '')
    {
        foreach ($files as $file){
            /** @var UploadedFile $file */
            $originalName = $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $i = 1;
            while ($this->storage->exists("$path/$name.$ext")){
                $name = $originalName . "($i)";
                $i++;
            }
            $file->storeAs($path, $name . '.' . $ext);
        }
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

    public function getFile($path)
    {
        if (!$this->storage->exists($path)) {
            abort(404);
        }
        $file = $this->storage->get($path);
        $type = mime_content_type(storage_path('app/'.$path));

        $response = \Response::make($file);
        $response->header('Content-Type', $type);

        return $response;
    }

    protected function makeItems($path, $search, $type)
    {
        $items = [];
        try {
            $_items = $type === 'file' ? $this->storage->files($path) : $this->storage->directories($path);
        } catch (\Exception $e) {
            throw new FileManagerException('Can not read files. Check permissions');
        }
        foreach ($_items as $_item) {
            $items[] = new $this->types[$type]($_item, $path);
        }

        if ($search) {
            $items = array_filter($items, function ($item) use ($search) {
               return strpos($item->name, $search) !== false;
            });
        }

        return $items;
    }

    protected function getStorage()
    {
        $disk = config('slfm.disk') ? : 'local';
        return app('filesystem')->disk($disk);
    }

}
