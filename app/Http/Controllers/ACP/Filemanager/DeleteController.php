<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Lang;

/**
 * Class CropController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class DeleteController extends LfmController {

    /**
     * Delete image and associated thumbnail
     *
     * @return mixed
     */
    public function getDelete()
    {
        $name_to_delete = Input::get('items');

        $file_path = parent::getPath();

        $file_to_delete = $file_path . $name_to_delete;
        $thumb_to_delete = parent::getPath('thumb') . $name_to_delete;

//        if (!Storage::disk('s3')->exists($file_to_delete)) {
//            return $file_to_delete . ' not found!';
//        }

//        if (Storage::disk('s3')->isDirectory($file_to_delete)) {
//            if (sizeof(Storage::disk('s3')->files($file_to_delete)) != 0) {
//                return Lang::get('lfm.error-delete');
//            }
        try {
            @Storage::disk('s3')->deleteDirectory($file_to_delete);

//            return 'OK';
//        }
        }catch(\Exception $e){}
        try {

//            return 'OK';
//        }

            @Storage::disk('s3')->delete($file_to_delete);


        }catch(\Exception $e){}
        try {

            if (Session::get('lfm_type') === 'Images') {
                @Storage::disk('s3')->delete($thumb_to_delete);
            }
        }catch(\Exception $e){}


        return 'OK';
    }
    
}
