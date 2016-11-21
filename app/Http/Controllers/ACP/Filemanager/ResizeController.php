<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

/**
 * Class ResizeController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class ResizeController extends LfmController {

    /**
     * Dipsplay image for resizing
     *
     * @return mixed
     */
    public function getResize()
    {
        $ratio = 1.0;
        $image = Input::get('img');

//        $path_to_image   = parent::getPath() . $image;
        $path_to_image   = 'https://s3.' . Config::get('filesystems.disks.s3.region') . '.amazonaws.com/' . Config::get('filesystems.disks.s3.bucket') . '/' . parent::getPath() . $image;
        $original_width  = Image::make($path_to_image)->width();
        $original_height = Image::make($path_to_image)->height();

        $scaled = false;

        if ($original_width > 600) {
            $ratio  = 600 / $original_width;
            $width  = $original_width  * $ratio;
            $height = $original_height * $ratio;
            $scaled = true;
        } else {
            $width  = $original_width;
            $height = $original_height;
        }

        if ($height > 400) {
            $ratio  = 400 / $original_height;
            $width  = $original_width  * $ratio;
            $height = $original_height * $ratio;
            $scaled = true;
        }

        return View::make('Filemanager.resize')
            ->with('img', parent::getUrl() . $image)
            ->with('height', number_format($height, 0))
            ->with('width', $width)
            ->with('original_height', $original_height)
            ->with('original_width', $original_width)
            ->with('scaled', $scaled)
            ->with('ratio', $ratio);
    }


    public function performResize()
    {
        $img    = Input::get('img');
//        $dataX  = Input::get('dataX');
//        $dataY  = Input::get('dataY');
        $height = Input::get('dataHeight');
        $width  = Input::get('dataWidth');

        $path_to_image   = 'https://s3.' . Config::get('filesystems.disks.s3.region') . '.amazonaws.com/' . Config::get('filesystems.disks.s3.bucket') . '/public' . $img;


        try {
            $img = Image::make($path_to_image)->resize($width, $height);
            Storage::disk('s3')->put('/public' . $img, (string) $img->encode());

            return 'OK';
        } catch (Exception $e) {
//            return "width : " . $width . " height: " . $height;
            return $e;
        }

    }

}
