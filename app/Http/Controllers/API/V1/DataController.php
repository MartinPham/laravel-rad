<?php namespace App\Http\Controllers\API\V1;



use App\Club;
use App\Geo;
use App\Image;
use App\UserItemCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class DataController extends Controller {
    public function images()
    {
        $this->data = Image::orderBy('idx', 'desc')->orderBy('created_at', 'desc')->get();

        return $this->respond();
    }
}