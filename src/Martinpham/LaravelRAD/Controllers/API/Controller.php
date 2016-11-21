<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:11
 */

namespace Martinpham\LaravelRAD\Controllers\API;



use App\User;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

trait Controller
{
    public $ok = true;
    public $code = 1;
    public $error = array();
    public $msg = '';
    public $auth = false;
    public $user = false;


    public function __construct(Request $request, ResponseFactory $responseFactory, ConfigRepository $configRepository)
    {
        parent::__construct($request, $responseFactory);


        if($request->get('tz') !== '')
        {
            $configRepository->set('app.timezone', $request->get('tz'));
        }

    }

    public function respond($_data = null)
    {

        header('Content-Type: application/json');

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        if (isset($_data, $_data['ok'])) {
            $this->ok = $_data['ok'];
        }
        if (isset($_data, $_data['code'])) {
            $this->code = $_data['code'];
        }
        if (isset($_data, $_data['data'])) {
            $this->data = $_data['data'];
        }
        if (isset($_data, $_data['msg'])) {
            $this->msg = $_data['msg'];
        }
        if (isset($_data, $_data['auth'])) {
            $this->auth = $_data['auth'];
        }
        if (isset($_data, $_data['error'])) {
            $this->error = $_data['error'];
        }
        if (isset($_data, $_data['user'])) {
            $this->user = $_data['user'];
        }

//        if(Input::get('logout') == 1)
//        {
//            $this->auth = "";
//        }

//        die(Input::get('token'));

        $r = array(
            // 'tz' => Config::get('app.timezone'),
            // 't' => \Carbon\Carbon::now()->setTimezone(Config::get('app.timezone')),
            'ok' => $this->ok,
            'code' => $this->code,
            'data' => $this->data,
            'msg' => $this->msg,
            'error' => $this->error,
            'auth' => $this->auth ? : self::generateToken(),
            'user' => $this->user ? : self::user()
        );


        return $this->respond->json($r);

//        $response = Response::json($r, $this->ok ? 200 : 400);
////        $response->header('Content-Type', 'text/plain');
//        $response->header('Content-Type', 'application/json');
//        return $response;
    }

    public static function sharedInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static(request(), response(), config());
        }
        return $instance;
    }

    public static function unauthorized()
    {
        self::sharedInstance()->respond(array(
            'ok' => false,
            'code' => -999,
            'msg' => 'Unauthorized',
        ))->sendContent();
        die;
    }

//    public static function test()
//    {
//        return Controller::sharedInstance()->respond(array(
//            'ok' => true,
//            'code' => 999,
//            'msg' => 'hello nobody',
//        ));
//    }
//
//    public static function testAuth()
//    {
//        return Controller::sharedInstance()->respond(array(
//            'ok' => true,
//            'code' => 999,
//            'msg' => "hello " . self::user()->id,
//        ));
//    }


//    public static function getAllHeaders()
//    {
//        $headers = '';
//        foreach ($_SERVER as $name => $value) {
//            if (substr($name, 0, 5) == 'HTTP_') {
//                $headers[str_replace(' ', '-', (strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
//            }
//        }
//        return $headers;
//    }

//    public static function attempt($credentials)
//    {
//        return User::login($credentials['email'], $credentials['password']);
//    }


//    public static function loginById($id)
//    {
//        $user = User::find($id);
//
//        if (!$user) return false;
//
//        self::updateToken(generateToken($user));
//
//        return $user;
//    }

    public static function updateToken($token)
    {
        self::sharedInstance()->request->replace(['token' => $token]);
//        Input::replace(array());
//        \JWTAuth::setToken($token);
    }

    public static function user()
    {
        // dd(\JWTAuth::setToken(self::sharedInstance()->request->get('token'))->authenticate());
        /*
        $auth = Input::get('auth');
        if (!$auth) {
            $header = self::getAllHeaders();
            $auth = @$header['auth'];
        }

        if (!$auth) return false;


        try {
            list($id, $hash) = explode("@@@", $auth);


            $user = User::find($id);
            if (!$user) return false;
            if (!$user->activated) return false;

            $checkHash = self::generateToken($user);

            if ($id . "@@@" . $hash != $checkHash) return false;


        } catch (\Exception $e) {
            dd($e);
            $user = false;
        }


        return $user;
        */

//        var_dump(\JWTAuth::parseToken());
//        die;

        try {

            if (! $user = \JWTAuth::setToken(self::sharedInstance()->request->get('token'))->authenticate()) {
                return false;
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return false;

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return false;

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return false;

        }

        return $user;
    }

    /**
     * @return User
     */
    public static function auth()
    {
        $user = self::user();
        if(!$user){
            self::unauthorized();
        }

        return $user;
    }

    public static function generateToken($user = false)
    {
        if (!$user){
            $user = self::user();
        }
        if (!$user) {
            return false;
        }

        /*
        return trim($user->id) . '@@@' . md5(trim($user->id) . $user->password . trim($user->id) . Config::get('app.key'));
        */

        return \JWTAuth::fromUser($user);
    }
}
