<?php

namespace App\Exceptions;


use Exception;

class ApiException extends Exception
{
    const DEFAULT_ERROE = 1;

    const AUTHENTICATION_FAILED = 101;
    const LOGIN_HAS_EXPIRED = 102;
    const TOKEN_IS_INVALID = 103;
    const TOKEN_HAS_EXITED = 104;
    const TOKEN_IS_EMPTY = 105;

    const USERNAME_DOES_NOT_EXIST = 201;
    const PASSWORD_DOES_NOT_EXIST = 202;
    const THE_MERCHANT_HAS_BEEN_DISABLED = 203;
    const THE_MERCHANT_HAS_EXPIRED = 204;
    const USER_NAME_OR_PASSWORD_IS_WRONG = 205;

    const THE_REMAINING_AUTHORIZED_NUMBER_OF_U_DISK_IS_0 = 301;
    const PHYSICAL_SERIAL_NUMBER_IS_EMPTY = 302;
    const PHYSICAL_SERIAL_NUMBER_ERROR = 303;
    const LOGICAL_SERIAL_NUMBER_IS_EMPTY = 304;
    const LOGICAL_SERIAL_NUMBER_ERROR = 305;
    const INSUFFICIENT_NUMBER_OF_USB_FLASH_DRIVES = 306;
    const U_DISK_ENCRYPTION_NUMBER_HAS_REACHED_THE_MAXIMUM = 307;
    const U_DISK_ENCRYPTION_FAILED = 308;
    const THE_SYSTEM_IS_BUSY_PLEASE_TRY_AGAIN_LATER = 309;
    const BUSINESS_ID_IS_EMPTY = 310;
    const BUSINESS_ID_ERROR = 311;
    const THE_MERCHANT_DOES_NOT_EXIST = 312;
    const U_DISK_IS_DISABLED = 313;
    const U_DISK_IS_NOT_VALID = 314;
    const U_DISK_HAS_EXPIRED = 315;
    const U_DISK_RUNNING_TIMES_REACHED_THE_MAXIMUM = 316;
    const THE_MERCHANT_CONFIGURATION_IS_INCORRECT = 317;
    const SERVICE_EXCEPTION = 318;
    const NO_UPDATE_STRATEGY = 319;
    const EVENT_NAME_CANNOT_BE_EMPTY = 320;
    const THE_MACHINE_CODE_CANNOT_BE_EMPTY = 321;
    const U_DISK_CAPACITY_IS_EMPTY = 322;
    const U_DISK_CAPACITY_ERROR = 323;

    public static $errorMsg = [
        self::AUTHENTICATION_FAILED => '认证失败',
        self::LOGIN_HAS_EXPIRED => '登录已失效',
        self::TOKEN_IS_INVALID => 'token无效',
        self::TOKEN_HAS_EXITED => 'token已退出',
        self::TOKEN_IS_EMPTY => 'token为空',

        self::USERNAME_DOES_NOT_EXIST => '用户名不存在',
        self::PASSWORD_DOES_NOT_EXIST => '密码不存在',
        self::THE_MERCHANT_HAS_BEEN_DISABLED => '商户已禁用',
        self::THE_MERCHANT_HAS_EXPIRED => '商户已到期',
        self::USER_NAME_OR_PASSWORD_IS_WRONG => '用户名或密码错误',

        self::THE_REMAINING_AUTHORIZED_NUMBER_OF_U_DISK_IS_0 => 'U盘剩余授权数量为0',
        self::PHYSICAL_SERIAL_NUMBER_IS_EMPTY => '物理序列号为空',
        self::PHYSICAL_SERIAL_NUMBER_ERROR => '物理序列号错误',
        self::LOGICAL_SERIAL_NUMBER_IS_EMPTY => '逻辑序列号为空',
        self::LOGICAL_SERIAL_NUMBER_ERROR => '逻辑序列号错误',
        self::INSUFFICIENT_NUMBER_OF_USB_FLASH_DRIVES => '商户可授权U盘数量不足',
        self::U_DISK_ENCRYPTION_NUMBER_HAS_REACHED_THE_MAXIMUM => 'U盘加密次数已达到最大',
        self::U_DISK_ENCRYPTION_FAILED => 'U盘加密失败',
        self::THE_SYSTEM_IS_BUSY_PLEASE_TRY_AGAIN_LATER => '系统繁忙，请稍后重试',
        self::BUSINESS_ID_IS_EMPTY => '商家ID为空',
        self::BUSINESS_ID_ERROR => '商家ID错误',
        self::THE_MERCHANT_DOES_NOT_EXIST => '商家不存在',
        self::U_DISK_IS_DISABLED => 'U盘被禁用',
        self::U_DISK_IS_NOT_VALID => 'U盘未生效',
        self::U_DISK_HAS_EXPIRED => 'U盘已过期',
        self::U_DISK_RUNNING_TIMES_REACHED_THE_MAXIMUM => 'U盘运行次数达到最大',
        self::THE_MERCHANT_CONFIGURATION_IS_INCORRECT => '商家配置有误',
        self::SERVICE_EXCEPTION => '服务异常',
        self::NO_UPDATE_STRATEGY => '无更新策略',
        self::EVENT_NAME_CANNOT_BE_EMPTY => '事件名不能为空',
        self::THE_MACHINE_CODE_CANNOT_BE_EMPTY => '机器码不能为空',
        self::U_DISK_CAPACITY_IS_EMPTY => 'U盘容量为空',
        self::U_DISK_CAPACITY_ERROR => 'U盘容量错误',
    ];

    public function __construct($error)
    {
        if (isset(self::$errorMsg[$error])) {
            $this->message = self::$errorMsg[$error];
            $this->code = $error;
        } else {
            $this->message = $error;
            $this->code = self::DEFAULT_ERROE;
        }
    }

}
