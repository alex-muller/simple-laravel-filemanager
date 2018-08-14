<?php
$namespace = 'Muller\Filemanager\Http\Controllers';
$prefix = 'slfm';



Route::namespace($namespace)->prefix($prefix)->middleware('web')->group(function (){
    Route::get('/files/{path}', 'FileController@getFile')->where('path', '.*');;
    Route::get('/files', 'FileController@index');
    Route::put('/folder', 'FileController@createFolder');
    Route::post('/delete', 'FileController@delete');
    Route::post('/upload', 'FileController@upload');

    Route::get('/', function (){
        return view('muller::index');
    });
});
