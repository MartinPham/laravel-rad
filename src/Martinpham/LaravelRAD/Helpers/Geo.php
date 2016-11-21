<?php
namespace App\RAD\Helpers;


use App\RAD\Exceptions\InvalidModelClass;

class Geo
{
    public $class = false;
    public $query = [];
    public $center = [];
    public $limit = 0;
    public $skip = 0;
    public $distance = 1000000000000000;
    public $nameSorting = 1;
    public $distanceSorting = 1;
    public $withs = [];

    public function __construct($class = false)
    {
        if(!$class)
        {
            throw new InvalidModelClass('Class is invalid');
        }
        $this->class = $class;
        $this->center = [];

        $this->where('deleted_at', '$exists', false);
    }

    public function with($relationship)
    {
        if(!in_array($relationship, $this->withs, true)){
            $this->withs[] = $relationship;
        }

        return $this;
    }
    public function where($arg1, $arg2, $arg3 = null)
    {

        if($arg3 === null)
        {
            $this->query[] = [ $arg1 => $arg2 ];
        }else{
            if($arg2 === '=')
            {
                $this->query[] = [ $arg1 => $arg3 ];
            }elseif($arg2 === '>=')
            {
                $this->query[] = [ $arg1 => [ '$gte' => $arg3 ] ];
            }elseif($arg2 === '>')
            {
                $this->query[] = [ $arg1 => [ '$gt' => $arg3 ] ];
            }elseif($arg2 === '<=')
            {
                $this->query[] = [ $arg1 => [ '$lte' => $arg3 ] ];
            }elseif($arg2 === '<')
            {
                $this->query[] = [ $arg1 => [ '$lt' => $arg3 ] ];
            }elseif($arg2 === '!=')
            {
                $this->query[] = [ $arg1 => [ '$ne' => $arg3 ] ];
            }elseif($arg2 === 'in')
            {
                $this->query[] = [ $arg1 => [ '$in' => $arg3 ] ];
            }elseif($arg2 === 'nin')
            {
                $this->query[] = [ $arg1 => [ '$nin' => $arg3 ] ];
            }elseif($arg2 === 'eq')
            {
                $this->query[] = [ $arg1 => [ '$eq' => $arg3 ] ];
            }elseif($arg2 === 'elemMatch')
            {
                $this->query[] = [ $arg1 => [ '$elemMatch' => $arg3 ] ];
            }elseif($arg2 === 'like')
            {
                if($arg3 !== '') {
                    if (is_array($arg1)) {
                        $q = [];
                        foreach ($arg1 as $a1) {
                            $q[] = [$a1 => new \MongoDB\BSON\Regex('' . $arg3, 'i')];

                        }
                        $this->query[] = ['$or' => $q];
                    } else {
                        $this->query[] = [$arg1 => new \MongoDB\BSON\Regex('' . $arg3, 'i')];
                    }
                }
            }else
            {
                $this->query[] = [ $arg1 => [ $arg2 => $arg3 ] ];
            }
        }

        return $this;
    }

    public function whereIn($arg1, $arg2)
    {

        return $this->where($arg1, 'in', $arg2);
    }

    public function take($take)
    {
        $this->limit = $take;
        return $this;
    }

    public function skip($skip)
    {
        $this->skip = $skip;
        return $this;
    }

    public function aggregate()
    {
        $aggregates = [];
        if(empty($this->center))
        {
            $aggregates[] = [
                '$match' => ['$and' => $this->query],
            ];
        }else{
            $geoNear = [
                'near' => [
                    'type' => 'Point',
                    'coordinates' => $this->center
                ],
                'distanceField' => 'distance',
                'maxDistance' => $this->distance,
                'spherical' => true,
                'limit' => 2147483647,
                'query' => ['$and' => $this->query],
            ];

            // dd($geoNear);
            $aggregates[] = [
                '$geoNear' => $geoNear
            ];
        }



        $sort = [];
        if($this->distanceSorting !== false)
        {
            $sort['distance'] = $this->distanceSorting;
        }
        if($this->nameSorting !== false)
        {
            $sort['name'] = $this->nameSorting;
        }
        $aggregates[] = [
            '$sort' => $sort
        ];
        return $aggregates;
    }

    public function count()
    {
        $aggregates = $this->aggregate();
        $aggregates[] = [
            '$group' => [
                '_id' => null,
                'total' => [
                    '$sum' => 1
                ]
            ]
        ];

        $class = $this->class;
        $result = $class::raw()->aggregate($aggregates);

        /** @noinspection ForeachSourceInspection */
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach($result as $v){
            return $v->total;
        }

        return 0;
    }

    public function get()
    {
//        dd($this->withs);
        $aggregates = $this->aggregate();

        $aggregates[] = [
            '$skip' => $this->skip
        ];

        if($this->limit === 0){
            $this->limit = 2147483647;
        }
        $aggregates[] = [
            '$limit' => $this->limit
        ];



//            dd($aggregates);
        $class = $this->class;
        $result = $class::raw()->aggregate($aggregates);



        $rs = [];

        $classObject = new $this->class();
        /** @noinspection ForeachSourceInspection */
        foreach($result as $r)
        {
            $i = clone $classObject;
            $i_data = Geo::toArray($r);
//            $i_data = iterator_to_array($r);

//            dd(Geo::toArray($r));
//            $i_data = $r->getArrayCopy();



//            array_walk_recursive($i_data, function(&$value, $key){
////                echo $key . ' ' . (is_object($value) ? get_class($value) : '') . '\n';
//                if($value instanceof \MongoDB\Model\BSONDocument)
//                {
//                    $value = $value->toArray();
//                }elseif($value instanceof \MongoDB\Model\BSONArray)
//                {
//                    $value = $value->toArray();
//                }
//            });
//            foreach($i_data as &$i_data_val)
//            {
//                if($i_data_val instanceof \MongoDB\Model\BSONDocument)
//                {
//                    $i_data_val = $i_data_val->getArrayCopy();
//                }elseif($i_data_val instanceof \MongoDB\Model\BSONArray)
//                {
//                    $i_data_val = $i_data_val->getArrayCopy();
//                }
//            }

            $i->forceFill($i_data);

            if(count($this->withs) > 0)
            {
                foreach($this->withs as $with)
                {
                    $i->$with = $i->$with;
                }
            }


//            dd($i);
            $rs[] = $i;

        }

        return collect($rs);
    }

    public static function toArray($object)
    {
        $array = iterator_to_array($object);
        foreach($array as $key => $value)
        {
            if($value instanceof \MongoDB\Model\BSONDocument)
            {
                $array[$key] = Geo::toArray($value);
            }elseif($value instanceof \MongoDB\Model\BSONArray)
            {
                $array[$key] = Geo::toArray($value);
            }
        }

        return $array;
    }
}