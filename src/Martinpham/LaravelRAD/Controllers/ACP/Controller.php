<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:11
 */

namespace Martinpham\LaravelRAD\Controllers\ACP;

use Martinpham\LaravelRAD\Models\Area;
use Martinpham\LaravelRAD\Exceptions\CouldNotMakeDirectoryException;
use App\User;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

trait Controller
{
    public $auth;
    public $config;

    public function __construct(Request $request, ResponseFactory $responseFactory, AuthGuard $authGuard, ConfigRepository $configRepository)
    {

        parent::__construct($request, $responseFactory);

        $this->auth = $authGuard;
        $this->config = $configRepository;

        $this->data['title'] = 'ACP';

        $this->data['template'] = $configRepository->get('acp.template');

        $this->data['primary_nav'] = function(){
            return self::navs($this->auth->user());
        };
    }


    public static function navs(User $user)
    {
        $primary_nav = [];


//        dd(Area::all());
        foreach(Area::all() as $item)
        {
            if($item->parent_id === '' && $item->menu && $user->can('access', $item))
            {
                $nav = [];
                $nav['name'] = $item->name;
                $nav['url'] = $item->id;
                $nav['alt_url'] = $item->url;
                $nav['icon'] = $item->icon;
                $nav['sub'] = [];


                /** @noinspection ForeachSourceInspection */
                foreach ($item->children as $sub_item) {
                    if (is_object($sub_item) && $sub_item->menu && $user->can('access', $sub_item)) {

                        $sub_nav = [];
                        $sub_nav['name'] = $sub_item->name;
                        $sub_nav['url'] = $sub_item->id;
                        $sub_nav['alt_url'] = $sub_item->url;
                        $sub_nav['icon'] = $sub_item->icon;
                        $sub_nav['sub'] = [];

//                        dd($sub_item->children);

                        /** @noinspection ForeachSourceInspection */
                        foreach ($sub_item->children as $sub_sub_item) {
                            if ($sub_sub_item->menu && $user->can('access', $sub_sub_item)) {

                                $sub_sub_nav = [];
                                $sub_sub_nav['name'] = $sub_sub_item->name;
                                $sub_sub_nav['url'] = $sub_sub_item->id;
                                $sub_sub_nav['url_ext'] = @$sub_sub_item->url_ext;
                                $sub_sub_nav['alt_url'] = $sub_sub_item->url;
                                $sub_sub_nav['icon'] = $sub_sub_item->icon;

                                $sub_nav['sub'][] = $sub_sub_nav;


                            }

                        }

                        $nav['sub'][] = $sub_nav;


                    }

                }


                $primary_nav[] = $nav;
            }
        }


//         dd($primary_nav);
        return $primary_nav;
    }


    public function upload($field, $oname, $fname)
    {
        if(!@mkdir('uploads/') && !is_dir('uploads/'))
        {
            throw new CouldNotMakeDirectoryException('Could not make directory');
        }
        if(!@mkdir('uploads/' . $oname) && !is_dir('uploads/' . $oname))
        {
            throw new CouldNotMakeDirectoryException('Could not make directory');
        }

        if (Input::hasFile($field)) {
            Input::file($field)->move('uploads/' . $oname . '/', $fname);
            return '/uploads/' . $oname . '/' . $fname;
        }

        return '';
    }
}