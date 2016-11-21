<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

/**
 * Class ItemsController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class ItemsController extends LfmController {


    /**
     * Get the images to load for a selected folder
     *
     * @return mixed
     */
    public function getItems()
    {
        $type = Input::get('type');
        $keyword = Input::get('keyword');
        $view = $this->getView($type);
        $path = $this->file_location . Input::get('working_dir');

        $files       = Storage::disk('s3')->files($path);
        list($files, $file_info)   = $this->getFileInfos($files, $type, $keyword);
        $directories = parent::getDirectories($path);
        $thumb_url   = parent::getUrl('thumb');
//        $thumb_url   = 'https://s3.' . Config::get('filesystems.disks.s3.region') . '.amazonaws.com/' . Config::get('filesystems.disks.s3.bucket') . '/public/photos/thumbs/';

//        dd([$path, $files, $file_info, $directories, $thumb_url]);

        return View::make($view)
            ->with(compact('files', 'file_info', 'directories', 'thumb_url'));
    }
    

    private function getFileInfos($files, $type = 'Images', $keyword = '')
    {
        $file_info = [];

        foreach ((array)$files as $key => $file) {
            $file_name = parent::getFileName($file);

            if($keyword !== '' && strpos(strtolower($file_name), $keyword) === false)
            {
                unset($files[$key]);
                continue;
            }

//            $file_created = filemtime($file);

            $file_size = number_format(Storage::disk('s3')->size($file) / 1024, 2, '.', '');
            if ($file_size > 1024) {
                $file_size = number_format($file_size / 1024, 2, '.', '') . ' Mb';
            } else {
                $file_size .= ' Kb';
            }

            if ($type === 'Images') {
                $file_type = Storage::disk('s3')->mimeType($file);
                $icon = '';
            } else {
                $extension = strtolower(Storage::disk('s3')->extension($file_name));

                $icon_array = Config::get('lfm.file_icon_array');
                $type_array = Config::get('lfm.file_type_array');

                if (array_key_exists($extension, $icon_array)) {
                    $icon = $icon_array[$extension];
                    $file_type = $type_array[$extension];
                } else {
                    $icon = 'fa-file';
                    $file_type = 'File';
                }
            }

            $file_info[$key] = [
                'name'      => $file_name,
                'size'      => $file_size,
                'created'   => 0,//$file_created,
                'type'      => $file_type,
                'icon'      => $icon
            ];
        }

        return [$files, $file_info];
    }


    private function getView($type = 'Images')
    {
        $view = 'Filemanager.images';

        if ($type !== 'Images') {
            $view = 'Filemanager.files';
        }

        if (Input::get('show_list') === 1) {
            $view .= '-list';
        }

        return $view;
    }
}
