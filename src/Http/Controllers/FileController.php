<?php

namespace Muller\Filemanager\Http\Controllers;
use Illuminate\Http\Request;
use Muller\Filemanager\Factory;
use Muller\Filemanager\FileManager;

class FileController
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new FileManager();
    }

    public function index(Request $request)
    {
        $page = $request->get('page') ? : 1;
        $path = $request->get('path');
        $search = $request->get('search');
        $items = $this->manager->getItems($path, $page, $search, 12);

        return response()->json($items);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $this->manager->createFolder($request->input('name'), $request->input('path'));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'items' => 'required'
            ]);

        $this->manager->delete($request->post('items'));

    }
    public function upload(Request $request)
    {
        $this->manager->upload($request->file('files'), $request->input('path'));
    }

    public function getFile($path)
    {
        return $this->manager->getFile($path);
    }

}
