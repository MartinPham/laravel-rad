<?php namespace App\Http\Controllers\API\V1;



class TestController extends Controller {
    public function test()
    {
        $this->msg = 'v1 hello nobody';



        return $this->respond();
    }
    public function testAuth()
    {
        $this->msg = 'v1 hello ' . self::user()->id;
        return $this->respond();
    }
}