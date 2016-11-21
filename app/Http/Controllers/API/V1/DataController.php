<?php namespace App\Http\Controllers\API\V1;



use App\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class DataController extends Controller {
    public function items()
    {
        $this->data = Item::orderBy('created_at', 'desc')->get();

        return $this->respond();
    }
}