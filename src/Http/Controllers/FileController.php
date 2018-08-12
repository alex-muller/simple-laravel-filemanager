<?php

namespace Muller\Filemanager\Http\Controllers;
use Illuminate\Http\Request;
use Muller\Filemanager\AbstractFile;
use Muller\Filemanager\Factory;
use Muller\Filemanager\FileManager;

class FileController
{
    public function index(Request $request, FileManager $manager)
    {
        $page = $request->get('page') ? : 1;
        $path = $request->get('path');
        $items = $manager->getItems($path, $page, 12);

        return response()->json($items);
    }

    public function createFolder(Request $request, FileManager $manager)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $manager->createFolder($request->input('name'), $request->input('path'));
    }

    public function delete(Request $request, FileManager $manager)
    {
        $request->validate([
            'items' => 'required'
            ]);

        $manager->delete($request->post('items'));

    }
    public function upload(Request $request)
    {
        dd($request->all());
    }
}
