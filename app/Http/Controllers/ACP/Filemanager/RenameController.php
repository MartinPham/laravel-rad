<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Lang;

/**
 * Class RenameController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class RenameController extends LfmController {

    /**
     * @return string
     */
    public function getRename()
    {
        $old_name = Input::get('file');
        $new_name = Input::get('new_name');

        $file_path  = parent::getPath();
        $thumb_path = parent::getPath('thumb');

        $old_file = $file_path . $old_name;

        if (!File::isDirectory($old_file)) {
            $extension = File::extension($old_file);
            $new_name = str_replace('.' . $extension, '', $new_name) . '.' . $extension;
        }

        $new_file = $file_path . $new_name;

        if (File::exists($new_file)) {
            return Lang::get('lfm.error-rename');
        }

        if (File::isDirectory($old_file)) {
            File::move($old_file, $new_file);
            return 'OK';
        }
        
        File::move($old_file, $new_file);

        if (Session::get('lfm_type') === 'Images') {
            File::move($thumb_path . $old_name, $thumb_path . $new_name);
        }

        return 'OK';
    }
}
