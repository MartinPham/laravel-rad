<?php
/**
 * Created by: zzz
 * Date: 11/4/15
 * Time: 12:51 PM
 */

namespace Martinpham\LaravelRAD\Models;



class Area
{
    public $id;
    public $parent_id = '';
    public $name;
    public $icon;
    public $url = '';
    public $url_ext = '';
    public $menu = false;
    public $roles = [];
    public $children = [];

    public static function create($params = Array())
    {
        $area = new Area();
        $area->id = @$params['key'];
        $area->parent_id = @$params['parent_id'] ? : '';

        $area->name = @$params['name'];
        $area->icon = @$params['icon'];
        $area->menu = @$params['menu'];
        $area->url = @$params['url'] ? : '';
        $area->url_ext = @$params['url_ext'] ? : '';
        $area->roles = @$params['roles'];

        $area->children = self::parseTree($area->id, $params['children']);

        return $area;
    }

    public static function parseTree($parent_id = '', $tree_data = Array())
    {
        $tree = [];


        if(@is_string($tree_data))
        {
            try{

                $tree_data = eval('return ' . $tree_data . ';');
            }catch(\Exception $e){
//                    dd($e);
                $tree_data = [];
            }
        }

        foreach(@$tree_data as $key => $item)
        {
            $item['key'] = $key;
            $item['parent_id'] = $parent_id;
            $area = Area::create($item);


            $tree[] = $area;
        }


//        dd($tree);
//        if(count($tree_data) > 0) dd($tree_data);

        return collect($tree);
    }

    public static function all()
    {
        $list = (array)config('acp.area');

        return self::parseTree('', $list);
    }


//    public static function find($id)
//    {
//        $all = Area::all();
//        foreach($all as $item)
//        {
//            if($item->id === $id){
//                return $item;
//            }
//        }
//
//        return false;
//    }




}