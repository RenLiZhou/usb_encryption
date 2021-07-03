<?php

namespace App\Http\Controllers\Merchant;

use App\Service\ResourceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    public $v = 'merchant.file.';

    public function index(){
        return view($this->v . 'index');
    }

    public function files(Request $request){
        $path = $request->path??'/';

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $path = $ResourceService->checkPath($path);
        if(!$path){
            return responseError('路径错误');
        }

        $merchant_path = $merchant->root_directory.$path;

        $exists = $ResourceService->exists($merchant_path);
        if(!$exists){
            return responseError('该路径不存在');
        }

        //判断是不是文件夹
        $is_dir = $ResourceService->isDirectory($merchant_path);
        if(!$is_dir){
            return responseError('该路径不是文件夹');
        }

        //展示该路径下文件夹&&文件
        $datas = $ResourceService->getDirectoriesFiles($merchant_path, $merchant->root_directory);

        //上级目录
        $top_path = substr($path,0, strrpos($path, '/'));
        if(empty($top_path) && !empty($path) && $path != '/'){
            $top_path = '/';
        }

        $response_data = [
            'current_path' => $path,
            'top_path' => $top_path,
            'paths' => $datas
        ];

        return responseSuccess($response_data);
    }

    /**
     * 删除文件夹
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFiles(Request $request){
        $paths = $request->paths??[];
        if(empty($paths)){
            return responseError('无删除路径');
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $delete_paths = [];
        foreach ($paths as $path){
            $path = $ResourceService->checkPath($path);
            if(!$path){
                return responseError('删除路径错误');
            }

            $merchant_path = $merchant->root_directory.$path;
            $exists = $ResourceService->exists($merchant_path);
            if(!$exists){
                return responseError('删除路径不存在');
            }

            $delete_paths[] = $merchant_path;
        }

        foreach ($delete_paths as $path){
            //判断是不是文件夹
            $is_dir = $ResourceService->isDirectory($path);
            if($is_dir){
                $ResourceService->deleteDirectory($path);
            }else{
                $ResourceService->delete($path);
            }
        }

        //删除缓存
        $cache_key = "cache_merchant_folder_{$merchant->id}";
        Cache::forget($cache_key);

        return responseSuccess();
    }

    /**
     * 移动文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function move(Request $request){
        $paths = $request->paths??[];
        $move_path = $request->move_path??'';
        if(empty($paths)) return responseError('无源路径');
        if(empty($move_path)) return responseError('无目标路径');

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        //验证目标路径
        $move_path = $ResourceService->checkPath($move_path);
        if(!$move_path){
            return responseError('移动目标路径错误');
        }

        $target_move_path = $merchant->root_directory.$move_path;
        $exists = $ResourceService->exists($target_move_path);
        if(!$exists){
            return responseError('移动目标路径不存在');
        }

        $is_dir = $ResourceService->isDirectory($target_move_path);
        if(!$is_dir){
            return responseError('移动目标路径不是文件夹');
        }

        //验证源路径
        $source_paths = [];
        foreach ($paths as $path){
            $path = $ResourceService->checkPath($path);
            if(!$path){
                return responseError('移动源错误');
            }

            $move_path = $merchant->root_directory.$path;
            $exists = $ResourceService->exists($move_path);
            if(!$exists){
                return responseError('移动源不存在');
            }

            //跟目录不允许移动
            if($path == '/'){
                return responseError('跟目录不允许移动');
            }

            //不能是上级移动到下级
            if(strpos($target_move_path,$move_path) !== false){
                return responseError('不能移动到下级目录');
            }

            //是否存在同名
            $name = $ResourceService->basename($move_path);
            $namesake = $ResourceService->exists($target_move_path.'/'.$name);
            if($namesake){
                return responseError('目标文件夹下存在同名文件');
            }

            $source_paths[] = $move_path;
        }

        foreach ($source_paths as $path){
            $name = $ResourceService->basename($path);

            //未移动的自动过滤掉
            if($path != $target_move_path.'/'.$name){
                $ResourceService->move($path, $target_move_path.'/'.$name);
            }
        }

        //删除缓存
        $cache_key = "cache_merchant_folder_{$merchant->id}";
        Cache::forget($cache_key);

        return responseSuccess();
    }

    /**
     * 重命名
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(Request $request){
        $path = $request->path??'';
        $new_name = $request->name??'';
        if(empty($path)) return responseError('无重命名目标');
        if (empty($new_name) || !preg_match('/^[^\\\\\\/:*?\\"<>|]+$/', $new_name)){
            return responseError('命名不能为空且不包含\/:*?"<>|+$');
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        //验证源路径
        $path = $ResourceService->checkPath($path);
        if(!$path || $path == '/'){
            return responseError('源目标错误');
        }

        //目标不存在
        $rename_path = $merchant->root_directory.$path;
        $exists = $ResourceService->exists($rename_path);
        if(!$exists){
            return responseError('目标不存在');
        }

        //名称未修改
        $basename = $ResourceService->basename($rename_path);
        if($basename == $new_name){
            return responseError('名称未修改');
        }

        //是否存在同名
        //上级目录
        $top_path = substr($rename_path,0, strrpos($rename_path, '/'));
        if(empty($top_path)){
            return responseError('源目标错误');
        }

        $namesake = $ResourceService->exists($top_path.'/'.$new_name);
        if($namesake){
            return responseError('存在同名文件');
        }

        $ResourceService->move($rename_path, $top_path.'/'.$new_name);

        //删除缓存
        $cache_key = "cache_merchant_folder_{$merchant->id}";
        Cache::forget($cache_key);

        return responseSuccess();
    }

    /**
     * 新建文件夹
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFolder(Request $request){
        $name = $request->name??'';
        $path = $request->path??'/';

        if (empty($name) || !preg_match('/^[^\\\\\\/:*?\\"<>|]+$/', $name)){
            return responseError('文件夹名不能为空且不包含\/:*?"<>|+$');
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $path = $ResourceService->checkPath($path);
        if(!$path){
            return responseError('所属文件夹不存在');
        }

        $parent_path = $merchant->root_directory.$path;

        $exists = $ResourceService->exists($parent_path);
        if(!$exists){
            return responseError('所属文件夹不存在');
        }

        //判断是不是文件夹
        $is_dir = $ResourceService->isDirectory($parent_path);
        if(!$is_dir){
            return responseError('所属文件夹错误');
        }

        //是否存在同名
        $namesake = $ResourceService->exists($parent_path.'/'.$name);
        if($namesake){
            return responseError('目标文件夹下存在同名文件');
        }

        $ResourceService->makeDirectory($parent_path.'/'.$name);

        //删除缓存
        $cache_key = "cache_merchant_folder_{$merchant->id}";
        Cache::forget($cache_key);

        return responseSuccess();
    }

    /**
     * 上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request){

    }

    /**
     * 获取文件/文件夹
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function folderOrFiles(Request $request, $type){

        $merchant = Auth::guard('merchant')->user();
        $root_directory = $merchant->root_directory;
        $cache_key = "cache_merchant_{$type}_{$merchant->id}";

        $ResourceService = new ResourceService();

        if(!Cache::has($cache_key)){
            //展示该路径下文件夹&&文件
            if($type == 'file'){
                $datas = $ResourceService->allFiles($root_directory);
            }else{
                $datas = $ResourceService->allDirectories($root_directory);
            }

            $result_data = []; //数组用来存放，格式化后的数据
            $count_id = 0;
            foreach($datas as $key => $data){
                $data = str_replace($root_directory.'/',"", $data);

                $data_arr = explode('/', $data); //把str路径转数组
                $parent = '/'; //定义动态路径，放下找时的临时存放路径
                foreach($data_arr as $item){ //从顶级一级一级往下找
                    $pid = $parent == '/' ? 0 : $result_data[$parent]['id']; //查找父ID
                    $parent .= $parent == '/' ? $item : '/' . $item; //拼接路径
                    if(isset($result_data[$parent]))  //如果已存在，跳过
                        continue;

                    $is_dir = $ResourceService->isDirectory($root_directory . $parent);

                    $result_data[$parent] = array(
                        'id' => $count_id+1, //ID
                        'pid' => $pid, //父ID
                        'level' => substr_count($parent, '/') + 1, //层级
                        'text' => $item, //名称，如果是目录保留后面斜杠
                        'path' => $parent, //目录
                        'type' => $is_dir == true ? 'folder' : 'file'
                    );
                    $count_id ++;
                }
            }

            $response_datas[] = [
                'text' => '我的文档',
                'path' => '/',
                'type' => 'folder',
                'nodes' => listToTree($result_data)
            ];

            Cache::put($cache_key, $response_datas, 7200);
        }

        $response_datas = Cache::get($cache_key);

        return responseSuccess($response_datas);
    }
}
