<?php


/*
 * ACP Auth
 */
Route::group(array(
    'prefix' => '/auth',
    'namespace' => 'Auth',
    'as' => '.auth'
), function () {
    Route::any('/', 'LoginController@showLoginForm')->name('');
    Route::get('/login', 'LoginController@showLoginForm')->name('.login');
    Route::post('/login', 'LoginController@login')->name('.login_post');
    Route::any('/logout', 'LoginController@logout')->name('.logout');
});


/*
 * ACP Auth'ed only area
 */
Route::group(
    [
        'middleware' => ['acpauth']
    ],
    function () {

        /*
         * ACP Image
         */
        Route::group(array(
            'prefix' => '/image',
            'as' => '.image'
        ), function () {
            Route::any('/', 'ImageController@index')->name('');
            Route::get('/create', 'ImageController@create')->name('.create');
            Route::post('/create', 'ImageController@create_post')->name('.create_post');
            Route::any('/{id}/read', 'ImageController@read')->name('.read');
            Route::get('/{id}/update', 'ImageController@update')->name('.update');
            Route::post('/{id}/update', 'ImageController@update_post')->name('.update_post');
            Route::any('/{id}/delete', 'ImageController@delete')->name('.delete');
        });

        /*
         * ACP Dashboard
         */
        Route::group(array(), function () {
            Route::any('/', 'IndexController@index')->name('');
        });
    }
);

//
//$middlewares = \Config::get('lfm.middlewares');
//array_push($middlewares, 'App\Http\Middleware\MultiUser');
//
//// make sure authenticated
//Route::group(array('middleware' => $middlewares, 'prefix' => 'laravel-filemanager'), function ()
//{
//    // Show LFM
//    Route::get('/', 'Filemanager\LfmController@show');
//
//    // upload
//    Route::any('/upload', 'Filemanager\UploadController@upload');
//    Route::any('/uploadDD', 'Filemanager\UploadController@uploadDD');
//
//    // list images & files
//    Route::get('/jsonitems', 'Filemanager\ItemsController@getItems');
//
//    // folders
//    Route::get('/newfolder', 'Filemanager\FolderController@getAddfolder');
//    Route::get('/deletefolder', 'Filemanager\FolderController@getDeletefolder');
//    Route::get('/folders', 'Filemanager\FolderController@getFolders');
//
//    // crop
//    Route::get('/crop', 'Filemanager\CropController@getCrop');
//    Route::get('/cropimage', 'Filemanager\CropController@getCropimage');
//
//    // rename
//    Route::get('/rename', 'Filemanager\RenameController@getRename');
//
//    // scale/resize
//    Route::get('/resize', 'Filemanager\ResizeController@getResize');
//    Route::get('/doresize', 'Filemanager\ResizeController@performResize');
//
//    // download
//    Route::get('/download', 'Filemanager\DownloadController@getDownload');
//
//    // delete
//    Route::get('/delete', 'Filemanager\DeleteController@getDelete');
//});
//
//
//



