<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Lang;

/**
 * Class UploadController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class UploadController extends LfmController {

    /**
     * Upload an image/file and (for images) create thumbnail
     *
     * @return string
     */
    public function upload()
    {
        if (!Input::hasFile('upload')) {
            return Lang::get('lfm.error-file-empty');
        }

        $file = Input::file('upload');

        $new_filename = $this->getNewName($file);

        $dest_path = parent::getPath();

        if (Storage::disk('s3')->exists($dest_path . $new_filename)) {
            return Lang::get('lfm.error-file-exist');
        }

//        $file->move($dest_path, $new_filename);

        $content = file_get_contents($file->getRealPath());
        Storage::disk('s3')->put($dest_path .$new_filename, $content);

        if (Session::get('lfm_type') === 'Images') {
            $this->makeThumb($dest_path, $new_filename, $content);
        }

        // upload via ckeditor 'Upload' tab
        if (!Input::has('show_list')) {
            return $this->useFile($new_filename);
        }

        return 'OK';
    }
    public function uploadDD()
    {
        if (!Input::hasFile('upload')) {
            return Lang::get('lfm.error-file-empty');
        }

        $file = Input::file('upload');

        $new_filename = $this->getNewName($file);

        $dest_path = parent::getPath();

        if (Storage::disk('s3')->exists($dest_path . $new_filename)) {
            return Lang::get('lfm.error-file-exist');
        }

//        $file->move($dest_path, $new_filename);

        $content = file_get_contents($file->getRealPath());
        Storage::disk('s3')->put($dest_path .$new_filename, $content);

        if (Session::get('lfm_type') === 'Images') {
            $this->makeThumb($dest_path, $new_filename, $content);
        }

        return '{
    "uploaded": 1,
    "fileName": "'.$new_filename.'",
    "url": "'.'https://s3.' . Config::get('filesystems.disks.s3.region') . '.amazonaws.com/' . Config::get('filesystems.disks.s3.bucket') . '/public'. parent::getUrl() . $new_filename.'"
}';
    }

    private function getNewName($file)
    {
        $new_filename = $file->getClientOriginalName();

        if (Config::get('lfm.rename_file') === true) {
            $new_filename = uniqid('file', true) . '.' . $file->getClientOriginalExtension();
        }

        return $new_filename;
    }

    private function makeThumb($dest_path, $new_filename, $contents)
    {
        $thumb_folder_name = Config::get('lfm.thumb_folder_name');

        if (!Storage::disk('s3')->exists($dest_path . $thumb_folder_name)) {
            Storage::disk('s3')->makeDirectory($dest_path . $thumb_folder_name);
        }

//        $thumb_img = Image::make($dest_path . $new_filename);
        $thumb_img = Image::make($contents);
        $thumb_img->fit(200, 200);

//            ->save($dest_path . $thumb_folder_name . '/' . $new_filename);
        Storage::disk('s3')->put($dest_path . $thumb_folder_name . '/' . $new_filename, (string) $thumb_img->encode());

        unset($thumb_img);
    }

    private function useFile($new_filename)
    {
        $file = 'https://s3.' . Config::get('filesystems.disks.s3.region') . '.amazonaws.com/' . Config::get('filesystems.disks.s3.bucket') . '/public'. parent::getUrl() . $new_filename;

        return "<script type='text/javascript'>

        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);
            return ( match && match.length > 1 ) ? match[1] : null;
        }

        var funcNum = getUrlParam('CKEditorFuncNum');

        var par = window.parent,
            op = window.opener,
            o = (par && par.CKEDITOR) ? par : ((op && op.CKEDITOR) ? op : false);

        if (op) window.close();
        if (o !== false) o.CKEDITOR.tools.callFunction(funcNum, '$file');
        </script>";
    }

}
