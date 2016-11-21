<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 17:48
 */

namespace App\Http\Controllers\ACP;

use App\Item;

class ItemController extends Controller
{
    public $routeId = 'acp.item';
    public $modelClass = Item::class;

    public function index()
    {
        $this->data['items'] = $this->modelClass::orderBy('created_at', 'desc')->get();

        $this->data['routeId'] = $this->routeId;
        return $this->view();
    }

    public function create()
    {
        $this->data['routeId'] = $this->routeId;
        return $this->view('update');
    }

    public function create_post()
    {

        $input = $this->request->only([
            'name', 'photo'
        ]);

        $this->modelClass::create($input);

        return redirect(route($this->routeId));
    }

    public function update($id)
    {
        $this->data['item'] = $item = $this->modelClass::find($id);

        $this->data['routeId'] = $this->routeId;
        return $this->view();
    }

    public function read($id)
    {
        $this->data['item'] = $item = $this->modelClass::find($id);

        $this->data['routeId'] = $this->routeId;
        return $this->view();
    }

    public function update_post($id)
    {
        $this->data['item'] = $item = $this->modelClass::find($id);
        $input = $this->request->only([
            'name', 'photo'
        ]);
        
        $item->update($input);

        return redirect(route($this->routeId));
    }

    public function delete($id)
    {
        $this->modelClass::find($id)->delete();

        return redirect(route($this->routeId));
    }
}