<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class MerchantVersion extends BaseModel
{
    protected $guarded = [];
    public $appends = ['title_name'];

    public static function basicRules()
    {
        return [
            'name' => 'required',
            'disk_number' => 'required|numeric|min:0',
            'price' =>  'required|numeric|min:0',
            'extra_price' =>  'required|numeric|min:0',
        ];
    }

    public static function basicMessages()
    {
        return [
            'name.required' => '版本标识为空',
            'disk_number.required' => '可加密U盘数量错误',
            'disk_number.numeric' => '可加密U盘数量错误',
            'disk_number.min' => '可加密U盘数量错误',
            'price.required' => '价格错误',
            'price.numeric' => '价格错误',
            'price.min' => '价格错误',
            'extra_price.required' => '额外授权价格错误',
            'extra_price.numeric' => '额外授权价格错误',
            'extra_price.min' => '额外授权价格错误'
        ];
    }

    /**
     * 创建
     * @return string
     */
    public static function createData($params)
    {
        $name = $params['name']??'';
        $disk_number = $params['disk_number']??0;
        $extra_price = round($params['extra_price']??0,2);
        $price = round($params['price']??0,2);

        if(empty($name)){
            return resultError('版本标识为空');
        }

        $if_data = self::query()->where('name',$name)->exists();
        if($if_data){
            return resultError('该版本标识已存在');
        }

        try {
            $insert_info = [
                'name' => $name,
                'disk_number' => $disk_number,
                'price' => $price,
                'extra_price' => $extra_price
            ];

            $insert_result = self::query()->create($insert_info);

            if($insert_result){
                return resultSuccess();
            }

        } catch (\Exception $exception) {
            Log::info('创建商户版本异常:'.$exception->getMessage());
        }
        return resultError('创建版本失败');
    }


    /**
     * 更新
     * @return string
     */
    public static function updateData($id, $params)
    {
        $name = $params['name']??'';
        $disk_number = $params['disk_number']??0;
        $extra_price = round($params['extra_price']??0,2);
        $price = round($params['price']??0,2);

        if(empty($name)){
            return resultError('版本标识为空');
        }

        $data = self::query()->find($id);
        if(empty($data)){
            return resultError('该版本不存在');
        }

        $if_repetition = self::query()->where('name',$name)->where('id', '<>', $data->id)->exists();
        if($if_repetition){
            return resultError('该版本标识已存在');
        }

        try {
            $data->name = $name;
            $data->disk_number = $disk_number;
            $data->price = $price;
            $data->extra_price = $extra_price;
            $data->save();

            return resultSuccess();
        } catch (\Exception $exception) {
            Log::info('更新商户版本异常:'.$exception->getMessage());
            return resultError('更新商户版本异常');
        }
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getTitleNameAttribute()
    {
        return __("merchant_version.{$this->name}");
    }

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, MerchantVersionRelation::class, 'version_id', 'merchant_id');
    }

    public function rules()
    {
        return $this->belongsToMany(MerchantRule::class, MerchantVersionRule::class, 'version_id', 'rule_id');
    }

    public function ztreeRules()
    {
        $curRules = $this->rules()->pluck('merchant_rules.id')->toArray();
        $allRules = MerchantRule::select('id', 'title', 'pid')->orderBy('sort', 'asc')->get()->toArray();
        $rules = $this->buildZtreeData($allRules, $curRules);
        $rules[] = [
            "id"=>0,
            "pid"=>0,
            "title"=>"全部",
            "open"=>true
        ];
        return $rules;
    }

    public function buildZtreeData(array $data, array $checked, $pid = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                if (in_array($v['id'], $checked)) {
                    $v['checked'] = true;
                }
                $v['open'] = true;
                $v['title'] = $v['title_name'];
                $arr[] = $v;
                unset($data[$k]);
                $arr = array_merge($arr, $this->buildZtreeData($data, $checked, $v['id']));
            }
        }
        return $arr;
    }
}
