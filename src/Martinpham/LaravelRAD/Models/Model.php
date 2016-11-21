<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:12
 */

namespace Martinpham\LaravelRAD\Models;

use Martinpham\LaravelRAD\Exceptions\InvalidForeignField;
use Martinpham\LaravelRAD\Exceptions\InvalidForeignObject;
use Carbon\Carbon;
use MongoDB\BSON\ObjectID;


trait Model
{
//    use \Jenssegers\Mongodb\Eloquent\SoftDeletes;

//    protected $dates = ['deleted_at'];


    public static function MID($id)
    {
        if(is_string($id))
        {
            $id = new ObjectID($id);
        }

        return $id;
    }


    protected function asDateTime($value)
    {
        // Convert MongoDate instances.
        if ($value instanceof \MongoDB\BSON\UTCDateTime)
        {
            return Carbon::instance($value->toDateTime())->setTimezone(config('app.timezone'));
        }

        return parent::asDateTime($value);
    }


    public function saveRelationship($field)
    {
        $fkey = $field . '_id';
        $fclass = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
        $fclass = ('\\App\\' . $fclass);
        if(!@$this->{$fkey})
        {
            throw new InvalidForeignField('INVALID '  . strtoupper($field) . '_ID');
        }
        $fitem = $fclass::find($this->{$fkey});
        if(!@$fitem)
        {
            throw new InvalidForeignObject('INVALID_' . strtoupper($field) . ' ' . $this->{$fkey});
        }

        $this->{$fkey} = $fitem->id;

//        dd($this);

        $fitemArray = (object) $fitem->toArray();
        unset($fitemArray->_id);
//        unset($fitemArray->created_at);
//        unset($fitemArray->updated_at);
        $this->{$field . '_data'} = $fitemArray;
    }


}


