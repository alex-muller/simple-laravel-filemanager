<?php

namespace Muller\Filemanager\Http\Controllers;
use Illuminate\Http\Request;
use Muller\Filemanager\Factory;

class FileController
{
    public function index(Request $request)
    {
        $page = $request->get('page') ? : 1;
        $items = Factory::getItems('public', $page, 12);

        return response()->json($items);
    }
}
