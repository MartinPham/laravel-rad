<?php namespace App\Http\Controllers\ACP\Filemanager;

use App\Http\Controllers\ACP\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;


//use Martinpham\LaravelRAD\Models\Area;
//use Martinpham\LaravelRAD\Exceptions\CouldNotMakeDirectoryException;
//use App\User;
//use Illuminate\Contracts\Auth\Guard as AuthGuard;
//use Illuminate\Contracts\Config\Repository as ConfigRepository;
//use Illuminate\Contracts\Routing\ResponseFactory;
//use Illuminate\Http\Request;

/**
 * Class LfmController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class LfmController extends  \Illuminate\Routing\Controller  {

    /**
     * @var
     */
    public $file_location;
    public $dir_location;
    

    /**
     * Constructor
     */
//    public function __construct(Request $request, ResponseFactory $responseFactory, AuthGuard $authGuard, ConfigRepository $configRepository)
    public function __construct()
    {
//        parent::__construct($request, $responseFactory, $authGuard, $configRepository);

        $this->setPathAndType();
        
        $this->checkMyFolderExists();
        
        $this->checkSharedFolderExists();


    }


    /**
     * Show the filemanager
     *
     * @return mixed
     */
    public function show()
    {
        if (!Input::has('working_dir')) {
            $working_dir = '/';
        } else {
            $working_dir = Input::get('working_dir');
        }

        return View::make('Filemanager.index')
            ->with('working_dir', $working_dir);
    }


    /*****************************
     ***   Private Functions   ***
     *****************************/


    private function setPathAndType()
    {   
        if (Input::has('type') && Input::get('type') === 'Files') {
            Session::put('lfm_type', 'Files');
            $this->file_location = Config::get('lfm.files_dir');
            $this->dir_location = Config::get('lfm.files_url');
        } else {
            Session::put('lfm_type', 'Images');
            $this->file_location = Config::get('lfm.images_dir');
            $this->dir_location = Config::get('lfm.images_url');
        }
    }


    private function checkMyFolderExists()
    {
        if (\Config::get('lfm.allow_multi_user') === true) {
//            $path = base_path($this->file_location . Input::get('working_dir'));
            $path = ($this->file_location . Input::get('working_dir'));

            if (!Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->makeDirectory($path, $mode = 0777, true, true);
            }
        }
    }


    private function checkSharedFolderExists()
    {
//        $path = base_path($this->file_location . Config::get('lfm.shared_folder_name'));
        $path = ($this->file_location . Config::get('lfm.shared_folder_name'));

        if (!Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->makeDirectory($path, $mode = 0777, true, true);
        }
    }


    private function formatLocation($location, $type = null)
    {
        $working_dir = Input::get('working_dir');

        if ($working_dir !== '/') {
            $location .= $working_dir . '/';
        }

        if ($type === 'thumb') {
            $location = $location . Config::get('lfm.thumb_folder_name') . '/';
        }

        return $location;
    }


    /****************************
     ***   Shared Functions   ***
     ****************************/


    /**
     * @param string $type
     * @return string
     */
    public function getPath($type = null)
    {
//        $path = base_path() . '/' . $this->file_location;
        $path =  $this->file_location;

        $path = $this->formatLocation($path, $type);

        return $path;
    }


    /**
     * @param string $type
     * @return string
     */
    public function getUrl($type = null)
    {
        $url = $this->dir_location;

        $url = $this->formatLocation($url, $type);

        return $url;
    }


    public function getDirectories($path)
    {
        $thumb_folder_name = Config::get('lfm.thumb_folder_name');
        $all_directories = Storage::disk('s3')->directories($path);

        $arr_dir = [];

        foreach ((array) $all_directories as $directory) {
            $dir_name = $this->getFileName($directory);

            if ($dir_name !== $thumb_folder_name) {
                $arr_dir[] = $dir_name;
            }
        }

        return $arr_dir;
    }


    public function getFileName($file)
    {
        $path_parts = explode('/', $file);

        return end($path_parts);
    }
}
