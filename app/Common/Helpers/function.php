<?php

use Illuminate\Support\Carbon;

if (!function_exists('responseSuccess')) {
    /**
     * @param array $data
     * @param string $msg
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    function responseSuccess($data = [], string $msg = 'success', int $httpCode = \App\Common\Enum\HttpCode::OK)
    {
        $return = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($return, $httpCode);
    }
}

if (!function_exists('responseError')) {
    /**
     * @param string $errMsg
     * @param int $code
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    function responseError(string $errMsg, int $code = 1, int $httpCode = \App\Common\Enum\HttpCode::OK)
    {
        $errMsg = empty($errMsg) ? __('common.service_exception') : $errMsg;
        $return = [
            'code' => $code,
            'exception' => $errMsg
        ];
        return response()->json($return, $httpCode);
    }
}


if (!function_exists('resultError')) {
    /**
     * Error data
     * @return Arrar
     */
    function resultError($msg)
    {
        return [
            'result' => false,
            'msg' => $msg
        ];
    }
}

if (!function_exists('resultSuccess')) {
    /**
     * Success data
     * @return Arrar
     */
    function resultSuccess($data = null)
    {
        return [
            'result' => true,
            'data' => $data
        ];
    }
}

if (!function_exists('getRandomString')) {
    /**
     * 随机生成字符
     * @param $len
     * @return string
     */
    function getRandomString($len)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        mt_srand(10000000 * (double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}


if (!function_exists('conversionTime')) {
    /**
     * 时间
     * @param $len
     * @return string
     */
    function conversionTime($time, $format = 'Y-m-d H:i:s')
    {
        //当前设置的时区
        $timezone = request()->cookie('merchant_timezone')??'local';
        $date = "";
        if(!empty($time)){
            $ip_info = geoip()->getLocation(request()->ip());
            $offset = date_offset_get(date_create(null, timezone_open($ip_info->timezone)));

            if(request()->ip() == '127.0.0.1'){
                $offset = 28800;
            }

            if(strtolower($timezone) == 'local'){
                $date = Carbon::parse($time)->addSeconds($offset)->format($format);
            }else{
                $date = Carbon::parse($time)->format($format);
            }
        }

        return $date;
    }
}

if (!function_exists('conversionSetTime')) {
    /**
     * 随机生成字符
     * @param $len
     * @return string
     */
    function conversionSetTime($time, $format = 'Y-m-d H:i:s')
    {
        //当前设置的时区
        $timezone = request()->cookie('merchant_timezone')??'local';
        $date = "";

        if(!empty($time)){
            $ip_info = geoip()->getLocation(request()->ip());
            $offset = date_offset_get(date_create(null, timezone_open($ip_info->timezone)));

            if(request()->ip() == '127.0.0.1'){
                $offset = 28800;
            }

            if(strtolower($timezone) == 'local'){
                $date = Carbon::parse($time)->addSeconds($offset)->format($format);
            }else{
                $date = Carbon::parse($time)->format($format);
            }
        }

        return $date;
    }
}

if (!function_exists('checkResult')) {
    /**
     *
     * @param $result
     * @return bool
     */
    function checkResult($result){
        foreach ($result as $v) {
            if (!$v || empty($v)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('listToTree')) {
    /**
     * 列表转树形（迭代）
     * @param array $list
     * @param bool $useKey 是否使用ID作为键值
     * @return array
     */
    function listToTree($list, $useKey = false)
    {
        $list = array_column($list, null, 'id');
        foreach ($list as $key => $val) {
            if ($val['pid']) {
                if (isset($list[$val['pid']])) {
                    if ($useKey) {
                        $list[$val['pid']]['nodes'][$key] = &$list[$key];
                    } else {
                        $list[$val['pid']]['nodes'][] = &$list[$key];
                    }
                }
            }
        }
        foreach ($list as $key => $val) {
            if ($val['pid']) unset($list[$key]);
        }
        if ($useKey) {
            return $list;
        } else {
            return array_values($list);
        }
    }
}
if (!function_exists('getFilesize')) {
    /**
     * 转化大小
     * @param $num
     * @return string
     */
    function getFilesize($num)
    {
        $p = 0;
        $format = 'B';
        if ($num > 0 && $num < 1024) {
            $p = 0;
            return number_format($num) . ' ' . $format;
        }
        if ($num >= 1024 && $num < pow(1024, 2)) {
            $p = 1;
            $format = 'KB';
        }
        if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        }
        if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        }
        if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
            $p = 3;
            $format = 'TB';
        }
        $num /= pow(1024, $p);
        return number_format($num, 2) . $format;
    }
}
