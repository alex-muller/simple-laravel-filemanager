<?php
$namespace = 'Muller\Filemanager\Http\Controllers';
$prefix = 'slfm';



Route::namespace($namespace)->prefix($prefix)->middleware('web')->group(function (){
    Route::get('/files', 'FileController@index');

    Route::get('/', function (){
        return view('muller::index');
    });
});
