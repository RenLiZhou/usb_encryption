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
        $path = $request->input('path')??'/';

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $path = $ResourceService->checkPath($path);
        if(!$path){
            return responseError(__('merchant_controller.path_error'));
        }

        $merchant_path = $merchant->root_directory.$path;

        $exists = $ResourceService->exists($merchant_path);
        if(!$exists){
            return responseError(__('merchant_controller.the_path_does_not_exist'));
        }

        //判断是不是文件夹
        $is_dir = $ResourceService->isDirectory($merchant_path);
        if(!$is_dir){
            return responseError(__('merchant_controller.the_path_is_not_a_folder'));
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
        $paths = $request->input('paths')??[];
        if(empty($paths)){
            return responseError(__('merchant_controller.no_delete_path'));
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $delete_paths = [];
        foreach ($paths as $path){
            $path = $ResourceService->checkPath($path);
            if(!$path){
                return responseError(__('merchant_controller.delete_path_error'));
            }

            $merchant_path = $merchant->root_directory.$path;
            $exists = $ResourceService->exists($merchant_path);
            if(!$exists){
                return responseError(__('merchant_controller.delete_path_does_not_exist'));
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
        $this->clearCache($merchant->id);

        return responseSuccess();
    }

    /**
     * 移动文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function move(Request $request){
        $paths = $request->input('paths')??[];
        $move_path = $request->input('move_path')??'';
        if(empty($paths)) return responseError(__('merchant_controller.passive_path'));
        if(empty($move_path)) return responseError(__('merchant_controller.no_target_path'));

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        //验证目标路径
        $move_path = $ResourceService->checkPath($move_path);
        if(!$move_path){
            return responseError(__('merchant_controller.moving_destination_path_error'));
        }

        $target_move_path = $merchant->root_directory.$move_path;
        $exists = $ResourceService->exists($target_move_path);
        if(!$exists){
            return responseError(__('merchant_controller.the_moving_destination_path_does_not_exist'));
        }

        $is_dir = $ResourceService->isDirectory($target_move_path);
        if(!$is_dir){
            return responseError(__('merchant_controller.move_destination_path_is_not_a_folder'));
        }

        //验证源路径
        $source_paths = [];
        foreach ($paths as $path){
            $path = $ResourceService->checkPath($path);
            if(!$path){
                return responseError(__('merchant_controller.movement_source_error'));
            }

            $move_path = $merchant->root_directory.$path;
            $exists = $ResourceService->exists($move_path);
            if(!$exists){
                return responseError(__('merchant_controller.the_mobile_source_does_not_exist'));
            }

            //跟目录不允许移动
            if($path == '/'){
                return responseError(__('merchant_controller.my_document_is_not_allowed_to_be_moved'));
            }

            //不能是上级移动到下级
            if(strpos($target_move_path,$move_path) !== false){
                return responseError(__('merchant_controller.cannot_move_to_a_subordinate_directory'));
            }

            //是否存在同名
            $name = $ResourceService->basename($move_path);
            $namesake = $ResourceService->exists($target_move_path.'/'.$name);
            if($namesake){
                return responseError(__('merchant_controller.a_file_with_the_same_name_exists_in_the_target_folder'));
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
        $this->clearCache($merchant->id);

        return responseSuccess();
    }

    /**
     * 重命名
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(Request $request){
        $path = $request->input('path')??'';
        $new_name = $request->input('name')??'';
        if(empty($path)) return responseError(__('merchant_controller.no_renamed_files'));
        if (empty($new_name) || !preg_match('/^[^\\\\\\/:*?\\"<>|]+$/', $new_name)){
            return responseError(__('merchant_controller.the_name_cannot_be_empty_and_does_not_contain').'\/:*?"<>|+$');
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        //验证源路径
        $path = $ResourceService->checkPath($path);
        if(!$path || $path == '/'){
            return responseError(__('merchant_controller.source_target_error'));
        }

        //目标不存在
        $rename_path = $merchant->root_directory.$path;
        $exists = $ResourceService->exists($rename_path);
        if(!$exists){
            return responseError(__('merchant_controller.target_does_not_exist'));
        }

        //名称未修改
        $basename = $ResourceService->basename($rename_path);
        if($basename == $new_name){
            return responseError(__('merchant_controller.name_has_not_been_modified'));
        }

        //是否存在同名
        //上级目录
        $top_path = substr($rename_path,0, strrpos($rename_path, '/'));
        if(empty($top_path)){
            return responseError(__('merchant_controller.source_target_error'));
        }

        $namesake = $ResourceService->exists($top_path.'/'.$new_name);
        if($namesake){
            return responseError(__('merchant_controller.a_file_with_the_same_name_exists'));
        }

        $ResourceService->move($rename_path, $top_path.'/'.$new_name);

        //删除缓存
        $this->clearCache($merchant->id);

        return responseSuccess();
    }

    /**
     * 新建文件夹
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFolder(Request $request){
        $name = $request->input('name')??'';
        $path = $request->input('path')??'/';

        if (empty($name) || !preg_match('/^[^\\\\\\/:*?\\"<>|]+$/', $name)){
            return responseError(__('merchant_controller.the_folder_name_cannot_be_empty_and_does_not_contain').'\/:*?"<>|+$');
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $path = $ResourceService->checkPath($path);
        if(!$path){
            return responseError(__('merchant_controller.the_owning_folder_does_not_exist'));
        }

        $parent_path = $merchant->root_directory.$path;

        $exists = $ResourceService->exists($parent_path);
        if(!$exists){
            return responseError(__('merchant_controller.the_owning_folder_does_not_exist'));
        }

        //判断是不是文件夹
        $is_dir = $ResourceService->isDirectory($parent_path);
        if(!$is_dir){
            return responseError(__('merchant_controller.wrong_belonging_folder'));
        }

        //是否存在同名
        $namesake = $ResourceService->exists($parent_path.'/'.$name);
        if($namesake){
            return responseError(__('merchant_controller.a_file_with_the_same_name_exists_in_the_target_folder'));
        }

        $ResourceService->makeDirectory($parent_path.'/'.$name);

        //删除缓存
        $this->clearCache($merchant->id);

        return responseSuccess();
    }

    /**
     * 上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request){
        $file = $request->file('files');
        $path = $request->input('path')??'/';
        if (empty($file)) {
            return responseError(__('merchant_controller.file_not_uploaded'));
        }

        $merchant = Auth::guard('merchant')->user();
        $ResourceService = new ResourceService();

        $path = $ResourceService->checkPath($path);
        if(!$path){
            return responseError(__('merchant_controller.file_storage_directory_does_not_exist'));
        }

        $upload_path = $merchant->root_directory.$path;

        $exists = $ResourceService->exists($upload_path);
        if(!$exists){
            return responseError(__('merchant_controller.file_storage_directory_does_not_exist'));
        }

        //判断是不是文件夹
        $is_dir = $ResourceService->isDirectory($upload_path);
        if(!$is_dir){
            return responseError(__('merchant_controller.storage_directory_error'));
        }

        //判断文件是否上传成功
        if ($file->isValid()) {
            //文件扩展名
            $ext = strtolower($file->getClientOriginalExtension());

            //设置黑名单
            $valid_ext = ['php', 'php2', 'php3', 'php4', 'php5', 'html', 'htm', 'js', 'phtml', 'pht', 'jsp', 'jspa', 'jspx', 'jsw', 'sh', 'ini', 'htaccess'];
            if (in_array($ext, $valid_ext)) {
                return responseError(__('merchant_controller.upload_of_this_type_of_file_is_not_allowed'));
            }

            $clientName = $file->getClientOriginalName();//原名称

            //同名增加后缀
            $namesake = $ResourceService->exists($upload_path.'/'.$clientName);
            if($namesake){
                $clientName = str_replace(".".$ext,"",$clientName) . '-' . date('YmdHis') . "." . $ext;
            }

            //临时绝对路径
            $realPath = $file->getRealPath();

            //上传
            $is_upload = $ResourceService->put($upload_path.'/'.$clientName, file_get_contents($realPath));

            //判断是否上传成功
            if ($is_upload) {
                //删除缓存
                $this->clearCache($merchant->id);

                $response_data = [
                    'path' => ($path == '/'?'':$path) . '/' . $clientName,
                    'name' => $clientName
                ];
                return responseSuccess($response_data);
            } else {
                return responseError(__('merchant_controller.upload_failed'));
            }

        } else {
            return responseError(__('merchant_controller.upload_failed'));
        }
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
                        'type' => $is_dir == true ? 'folder' : 'file',
                        'size' => $ResourceService->size($root_directory.$parent)??0,
                    );
                    $count_id ++;
                }
            }

            $response_datas[] = [
                'text' => __('merchant_controller.my_documents'),
                'path' => '/',
                'type' => 'folder',
                'size' => 0,
                'nodes' => listToTree($result_data)
            ];

            Cache::put($cache_key, $response_datas, 7200);
        }

        $this->clearCache($merchant->id);

        return responseSuccess($response_datas);
    }

    public function clearCache($merchant_id){
        //删除文件夹缓存
        $cache_key = "cache_merchant_folder_{$merchant_id}";
        Cache::forget($cache_key);
        //删除文件缓存
        $cache_key = "cache_merchant_file_{$merchant_id}";
        Cache::forget($cache_key);
    }
}
