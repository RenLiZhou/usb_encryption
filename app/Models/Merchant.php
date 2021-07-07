<?php

namespace App\Models;

use App\Service\ResourceService;
use App\Service\MerchantVersionRulesService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authuser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Merchant extends Authuser
{
    public $guarded = ['id'];

    const PWD_COST     = 11; //密码加密

    const ACTIVE       = 1; //启用
    const NOT_ACTIVE   = 0; //未启用

    const NOT_PERMANENT       = 0; //非永久
    const PERMANENT   = 1; //永久

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        return conversionTime($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return conversionTime($value);
    }

    public static function createRules()
    {
        return [
            'name' => 'required',
            'username' => 'required|alpha_dash',
            'email' =>  'nullable|email',
            'expire_date' =>  'nullable|date',
            'version_id' =>  'required|integer|min:0',
            'language_id' =>  'required|integer|min:0',
            'status' =>  'required|integer|min:0',
            'password' =>  'required'
        ];
    }

    public static function updateRules()
    {
        return [
            'name' => 'required',
            'email' =>  'nullable|email',
            'expire_date' =>  'nullable|date',
            'version_id' =>  'required|integer|min:0',
            'language_id' =>  'required|integer|min:0',
            'status' =>  'required|integer|min:0',
            'add_auth_count' =>  'nullable|integer|min:0',
        ];
    }

    public static function basicMessages()
    {
        return [
            'name.required' => '商户名称为空',
            'username.required' => '商户用户名只能是字母、数字，-，_ ',
            'username.alpha_dash' => '商户用户名只能是字母、数字，-，_ ',
            'email.email' => '邮箱格式错误',
            'expire_date.date' => '有效期错误',
            'version_id.required' => '版本错误',
            'version_id.integer' => '版本错误',
            'version_id.min' => '版本错误',
            'language_id.required' => '语言错误',
            'language_id.integer' => '语言错误',
            'language_id.min' => '语言错误',
            'status.required' => '状态错误',
            'status.integer' => '状态错误',
            'status.min' => '状态错误',
            'password.required' => '密码错误',
            'add_auth_count.integer' => '额外授权数量错误',
            'add_auth_count.min' => '额外授权数量错误'
        ];
    }

    /**
     * 创建
     * @return string
     */
    public static function createData($params)
    {
        $name = $params['name']??'';
        $username = $params['username']??'';
        $email = $params['email']??'';
        $password = $params['password']??'';
        $expire_date = $params['expire_date']??0;
        $status = $params['status']??self::NOT_ACTIVE;
        $lang_id = $params['language_id']??0;
        $version_id = $params['version_id']??0;
        $expire_perpetual = $params['expire_perpetual']??0;
        $remarks = $params['remarks']??'';


        $unique = self::query()->where('username',$username)->exists();
        if($unique){
            return resultError('商户用户名已存在');
        }

        if(empty($expire_perpetual) && empty($expire_date)){
            return resultError('有效期错误');
        }

        $expire_time = NULL;
        if(!empty($expire_date)) $expire_time = conversionSetTime($expire_date);

        $is_permanent = self::NOT_PERMANENT;
        if($expire_perpetual == 1) $is_permanent = self::PERMANENT;

        try {
            $insert_info = [
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT, ['cost' => self::PWD_COST]),
                'expire_time' => $expire_time,
                'is_permanent' => $is_permanent,
                'status' => $status,
                'lang_id' => $lang_id,
                'remarks' => $remarks,
                'root_directory' => self::getRootDirectory()
            ];

            $createMerchant = self::query()->create($insert_info);

            if($createMerchant){
                $resource = new ResourceService();
                $resource->makeDirectory($insert_info['root_directory']);

                $createMerchant->version()->sync($version_id);
                return resultSuccess();
            }

        } catch (\Exception $exception) {
            Log::info('创建商户异常:'.$exception->getMessage());
        }
        return resultError('创建商户失败');
    }

    /**
     * 创建
     * @return string
     */
    public static function updateData($id, $params)
    {
        $merchant = self::query()->find($id);
        if(empty($merchant)){
            return resultError('该商户不存在');
        }

        $name = $params['name']??'';
        $email = $params['email']??'';
        $expire_date = $params['expire_date']??0;
        $expire_perpetual = $params['expire_perpetual']??0;
        $status = $params['status']?:self::NOT_ACTIVE;
        $lang_id = $params['language_id']??0;
        $version_id = $params['version_id']??0;
        $remarks = $params['remarks']??'';
        $add_auth_count = $params['add_auth_count']??0;
        $password = $params['password']??'';

        if(empty($expire_perpetual) && empty($expire_date)){
            return resultError('有效期错误');
        }

        $expire_time = NULL;
        if(!empty($expire_date)) $expire_time = conversionSetTime($expire_date);

        $is_permanent = self::NOT_PERMANENT;
        if($expire_perpetual == 1) $is_permanent = self::PERMANENT;

        try {
            $update_date = [
                'name' => $name,
                'email' => $email,
                'expire_time' => $expire_time,
                'is_permanent' => $is_permanent,
                'status' => $status,
                'lang_id' => $lang_id,
                'remarks' => $remarks,
                'add_auth_count' => $add_auth_count
            ];

            if(!empty($password)){
                $insert_info['password'] = password_hash($password, PASSWORD_DEFAULT, ['cost' => self::PWD_COST]);
            }

            $updateMerchant = $merchant->update($update_date);
            if($updateMerchant){
                $merchant->version()->sync($version_id);
                return resultSuccess();
            }

        } catch (\Exception $exception) {
            Log::info('更新商户异常:'.$exception->getMessage());
        }
        return resultError('更新商户失败');
    }

    /**
     * 获取可用跟目录
     * @return string
     */
    public static function getRootDirectory(){
        $filename = date('Ymd').substr(md5(time().rand(100000,999999)),16);

        $exists = Merchant::query()->where('root_directory', $filename)->exists();

        $resource = new ResourceService();
        $is_dir = $resource->exists($filename);

        if($exists || $is_dir){
            return self::getRootDirectory();
        }

        return $filename;
    }

    public function version()
    {
        return $this->belongsToMany(MerchantVersion::class, MerchantVersionRelation::class, 'merchant_id', 'version_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id', 'id');
    }


    /**
     * @return false|string
     */
    public function getExpireDateAttribute()
    {
        if($this->is_permanent == self::PERMANENT){
            return __('merchant_model.permanent');
        }
        return $this->expire_time;
    }

    /**
     * @return false|string
     */
    public function getExpireTimeAttribute($value)
    {
        return conversionTime($value);
    }

    public function getMerchantRules()
    {
        $version_rules = MerchantVersionRulesService::cacheRules();
        $vesion = MerchantVersionRelation::query()->where('merchant_id', $this->id)->first();
        return $vesion === null ? [] : $version_rules[$vesion->version_id] ?: [];
    }
}
