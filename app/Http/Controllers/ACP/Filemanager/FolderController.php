<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Lang;

/**
 * Class FolderController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class FolderController extends LfmController {

    /**
     * Get list of folders as json to populate treeview
     *
     * @return mixed
     */
    public function getFolders()
    {
        $dir_path = $this->file_location . \Auth::user()->user_field;
        $directories = parent::getDirectories($dir_path);


        $share_path = $this->file_location . Config::get('lfm.shared_folder_name');
        $shared_folders = parent::getDirectories($share_path);

        return View::make('Filemanager.tree')
            ->with('dirs', $directories)
            ->with('shares', $shared_folders);
    }


    /**
     * Add a new folder
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = Input::get('name');

        $path = parent::getPath() . $folder_name;

        if (!Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->makeDirectory($path, $mode = 0777, true, true);
            return 'OK';
        } else if (empty($folder_name)) {
            return Lang::get('lfm.error-folder-name');
        } else {
            return Lang::get('lfm.error-folder-exist');
        }
    }

}
