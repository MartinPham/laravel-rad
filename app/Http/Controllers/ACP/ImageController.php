<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 17:48
 */

namespace App\Http\Controllers\ACP;

use App\Image;

class ImageController extends Controller
{
    public function index()
    {
        $this->data['items'] = Image::orderBy('idx', 'desc')->orderBy('created_at', 'desc')->get();

        return $this->view();
    }

    public function create()
    {
        return $this->view('update');
    }

    public function create_post()
    {

        $input = $this->request->only([
            'name', 'idx', 'photo', 'lat', 'lng'
        ]);

        $input['geometry']['location']['lat'] = (float) $input['lat'];
        $input['geometry']['location']['lng'] = (float) $input['lng'];
        $input['idx'] = (int) $input['idx'];

        unset($input['lat'], $input['lng']);

        Image::create($input);

        return redirect(route('acp.image'));
    }

    public function update($id)
    {
        $this->data['item'] = $item = Image::find($id);

        return $this->view();
    }

    public function read($id)
    {
        $this->data['item'] = $item = Image::find($id);

        return $this->view();
    }

    public function update_post($id)
    {
        $this->data['item'] = $item = Image::find($id);
        $input = $this->request->only([
            'name', 'idx', 'photo', 'lat', 'lng'
        ]);
        $input['geometry']['location']['lat'] = (float) $input['lat'];
        $input['geometry']['location']['lng'] = (float) $input['lng'];
        $input['idx'] = (int) $input['idx'];

        unset($input['lat'], $input['lng']);

        $item->update($input);

        return redirect(route('acp.image'));
    }

    public function delete($id)
    {
        Image::find($id)->delete();

        return redirect(route('acp.image'));
    }
}