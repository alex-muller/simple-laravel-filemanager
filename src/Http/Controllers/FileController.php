<?php


namespace Muller\Filemanager\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class FileController
{
    public function index(Request $request)
    {
        $page = $request->get('page') ? : 1;
        $per_page = 12;
        $items = [
            [
                'name' => 'one',
                'type' => 'folder'
            ],
            [
                'name' => 'two',
                'type' => 'folder'
            ],
            [
                'name' => 'three',
                'type' => 'folder'
            ],
            [
                'name' => 'four',
                'type' => 'folder'
            ],
            [
                'name' => 'five',
                'type' => 'folder'
            ],
            [
                'name' => 'one',
                'type' => 'file'
            ],
            [
                'name' => 'two',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
            [
                'name' => 'three',
                'type' => 'file'
            ],
        ];
        $total = count($items);
        $offset = $per_page * ($page - 1);
        $items = array_slice($items, $offset, $per_page);
        $items = new LengthAwarePaginator($items, $total, $per_page, $page, ['path' => '/asd']);

        return response()->json($items);
    }
}
