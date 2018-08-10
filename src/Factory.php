<?php

namespace Muller\Filemanager;

use Illuminate\Pagination\LengthAwarePaginator;


class Factory
{
    public static function getItems($path, $page, $per_page)
    {
        $items = Folder::getItems($path);
        $items = array_merge($items, File::getItems($path));
        $total = count($items);
        $offset = $per_page * ($page - 1);
        $items = array_slice($items, $offset, $per_page);
        $items = new LengthAwarePaginator($items, $total, $per_page, $page, ['path' => $path]);

        return $items;
    }
}