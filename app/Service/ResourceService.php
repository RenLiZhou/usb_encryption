<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\Disk;
use App\Models\Merchant;
use App\Models\StrategyAuth;
use App\Models\StrategyUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ResourceService{
    public $resource_name = 'resource';
    public $resource;

    public function __construct()
    {
        $this->resource = Storage::disk($this->resource_name);
    }

    //是否是文件夹
    public function isDirectory($path){
        return File::isDirectory(public_path($this->resource_name.'/'.$path));
    }

    //是否是文件夹
    public function basename($path){
        return basename(public_path($this->resource_name.'/'.$path));
    }

    //删除文件夹
    public function deleteDirectory($path){
        return $this->resource->deleteDirectory($path);
    }

    //创建文件夹
    public function makeDirectory($path){
        return $this->resource->makeDirectory($path);
    }

    //文件夹及文件夹下所有文件夹
    public function allDirectories($path){
        return $this->resource->allDirectories($path);
    }

    //文件夹下所有文件夹
    public function directories($path){
        return $this->resource->directories($path);
    }

    //文件夹/文件是否存在
    public function exists($path){
        return $this->resource->exists($path);
    }

    //文件夹下所有文件
    public function files($path)
    {
        return $this->resource->files($path);
    }

    //文件夹及文件夹下所有文件
    public function allFiles($path)
    {
        return $this->resource->allFiles($path);
    }

    //删除文件
    public function delete($paths)
    {
        return $this->resource->delete($paths);
    }

    //文件最后修改时间
    public function lastModified($path)
    {
        return $this->resource->lastModified($path);
    }

    //文件最后修改时间
    public function size($path)
    {
        return $this->resource->size($path);
    }

    //移动文件
    public function move($path, $new_path)
    {
        return $this->resource->move($path, $new_path);
    }

    //是否是文件夹
    public function put($path, $contents, $options = []){
        return $this->resource->put($path, $contents, $options);
    }

    //文件夹下文件夹和文件
    public function getDirectoriesFiles($path, $root_directory){
        $data = [];
        $directories = $this->directories($path);
        foreach ($directories as $value){
            $item = [
                'path' => str_replace($root_directory,"",$value),
                'type' => 'folder',
                'size' => 0,
                'modify_time' => conversionTime($this->lastModified($value)),
                'name' => $this->basename($value)
            ];
            $data[] = $item;
        }

        $files = $this->files($path);
        foreach ($files as $value){
            $item = [
                'path' => str_replace($root_directory,"",$value),
                'type' => 'file',
                'size' => $this->size($value),
                'modify_time' => conversionTime($this->lastModified($value)),
                'name' => $this->basename($value)
            ];
            $data[] = $item;
        }

        return $data;
    }

    public function checkPath($path){

        if(empty($path)) return false;

        //第一个字符
        $frist_str = substr( $path, 0, 1 );
        if($frist_str != '/') return false;

        $path_arr = explode('/', $path);
        $path_arr = array_values(array_filter($path_arr));

        $data = '';
        foreach ($path_arr as $value){
            if(in_array($value, ['.','..'])){
                return false;
            }
            $data .= '/'.$value;
        }

        return empty($data) ? '/' : $data;
    }
}
