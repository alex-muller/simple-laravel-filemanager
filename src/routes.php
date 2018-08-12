<?php
$namespace = 'Muller\Filemanager\Http\Controllers';
$prefix = 'slfm';



Route::namespace($namespace)->prefix($prefix)->middleware('web')->group(function (){
    Route::get('/files', 'FileController@index');
    Route::put('/folder', 'FileController@createFolder');
    Route::post('/delete', 'FileController@delete');

    Route::get('/', function (){
        return view('muller::index');
    });
});
