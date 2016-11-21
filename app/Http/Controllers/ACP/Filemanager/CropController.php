<?php namespace App\Http\Controllers\ACP\Filemanager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

/**
 * Class CropController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class CropController extends LfmController {

    /**
     * Show crop page
     *
     * @return mixed
     */
    public function getCrop()
    {
        $working_dir = Input::get('working_dir');
        $img = parent::getUrl() . Input::get('img');

        return View::make('Filemanager.crop')
            ->with(compact('working_dir', 'img'));
    }


    /**
     * Crop the image (called via ajax)
     */
    public function getCropimage()
    {
        $image = Input::get('img');
        $dataX = Input::get('dataX');
        $dataY = Input::get('dataY');
        $dataHeight = Input::get('dataHeight');
        $dataWidth = Input::get('dataWidth');

        $path_to_image   = 'https://s3.' . Config::get('filesystems.disks.s3.region') . '.amazonaws.com/' . Config::get('filesystems.disks.s3.bucket') . '/public' . $image;

        // crop image
        $tmp_img = Image::make($path_to_image);
        $tmp_img->crop($dataWidth, $dataHeight, $dataX, $dataY);
        Storage::disk('s3')->put('/public' . $image, (string) $tmp_img->encode());

        // make new thumbnail
        $thumb_img = Image::make($path_to_image);
        $thumb_img->fit(200, 200);
        Storage::disk('s3')->put(parent::getPath('thumb') . parent::getFileName($image), (string) $thumb_img->encode());
    }

}
