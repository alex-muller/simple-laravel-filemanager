<?php


namespace Muller\Filemanager\Contracts;


interface File
{
    public function __construct(string $name, string $path);

    public static function getItems(string $path) : array;
}