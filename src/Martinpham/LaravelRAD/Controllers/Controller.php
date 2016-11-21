<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 18/11/16
 * Time: 15:11
 */

namespace Martinpham\LaravelRAD\Controllers;


use App\RAD\Exceptions\CouldNotMakeDirectoryException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

trait Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $id;
    public $data = array();
    public $viewDir = '';

    /** @var Request $request */
    public $request;

    /** @var ResponseFactory $request */
    public $response;

    public function __construct(Request $request, ResponseFactory $responseFactory)
    {
        $this->request = $request;
        $this->respond = $responseFactory;
    }

    public function view($vid = '')
    {
        $caller = caller();
        if ($this->id === null) {
            $this->id = str_replace(
                [
                    'App\\Http\\Controllers\\',
                    'Controller',
                    '\\'
                ],
                [
                    '',
                    '',
                    '/'
                ],
                $caller['class']
            );

        }
        if ($vid === '') {
            $vid = $caller['function'];
        }
        if (config('app.env') === 'local' && strpos($vid, '@') !== 0 && !file_exists(base_path() . '/resources/views/' . $this->viewDir . '/' . $this->id . '/' . $vid . '.blade.php')) {
            $paths = explode('/', $this->id);
            $max = count($paths);
            for ($i = 1; $i <= $max; $i++) {
                $path = implode('/', array_slice($paths, 0, $i));
                $dir = base_path() . '/resources/views/' . $this->viewDir . '/' . $path . '/';

                if(file_exists($dir) && !is_dir($dir))
                {
                    throw new CouldNotMakeDirectoryException('Path ' . $dir . ' is already exists, and it is not a directory');
                } /** @noinspection NotOptimalIfConditionsInspection */ elseif(!@mkdir($dir) && !is_dir($dir))
                {
                    throw new CouldNotMakeDirectoryException('Could not make directory ' . $dir);
                }


            }

            file_put_contents(base_path() . '/resources/views/' . $this->viewDir . '/' . $this->id . '/' . $vid . '.blade.php', '');

        }


        if (strpos($vid, '@') === 0) {
            $vid = substr($vid, 1);
        } else {
            $vid = $this->id . '.' . $vid;
        }

        $vid = str_replace("\\", '.', $vid);


        return view()->make($this->viewDir . '.' . $vid, $this->data);
    }


    /** @noinspection ArrayTypeOfParameterByDefaultValueInspection */
    public function missingMethod($parameters = Array())
    {
        return $this->view();
    }


    public static function validateData($input, $rules)
    {
        if(count($input) === 0){
            return true;
        }
        $validator = Validator::make($input, $rules);
        $returns = [];

        if($validator->fails())
        {
            $returns = $validator->messages();
        }

        return $returns;
    }
}


if (!function_exists('caller')) {
    function caller(
        $function = NULL,
        $use_stack = NULL)
    {
        $e = new \Exception();
        $trace = $e->getTrace();
        return $trace[2];
    }
}
if (!function_exists('d')) {
    function d()
    {
        echo '<pre>';
        foreach (func_get_args() as $v) {
            dump($v);
        }
        echo '</pre>';
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';
        foreach ((array)$_SERVER as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('base64_url_encode')) {
    function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }
}

if (!function_exists('base64_url_decode')) {
    function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
