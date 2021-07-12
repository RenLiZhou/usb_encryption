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

    public function __construct($msgCode)
    {
        $this->getMsg($msgCode);
    }

    public function getMsg ($msgCode){
        $msgArray = [
            self::AUTHENTICATION_FAILED => __('api.AUTHENTICATION_FAILED'),
            self::LOGIN_HAS_EXPIRED => __('api.LOGIN_HAS_EXPIRED'),
            self::TOKEN_IS_INVALID => __('api.TOKEN_IS_INVALID'),
            self::TOKEN_HAS_EXITED => __('api.TOKEN_HAS_EXITED'),
            self::TOKEN_IS_EMPTY => __('api.TOKEN_IS_EMPTY'),

            self::USERNAME_DOES_NOT_EXIST => __('api.USERNAME_DOES_NOT_EXIST'),
            self::PASSWORD_DOES_NOT_EXIST => __('api.PASSWORD_DOES_NOT_EXIST'),
            self::THE_MERCHANT_HAS_BEEN_DISABLED => __('api.THE_MERCHANT_HAS_BEEN_DISABLED'),
            self::THE_MERCHANT_HAS_EXPIRED => __('api.THE_MERCHANT_HAS_EXPIRED'),
            self::USER_NAME_OR_PASSWORD_IS_WRONG => __('api.USER_NAME_OR_PASSWORD_IS_WRONG'),

            self::THE_REMAINING_AUTHORIZED_NUMBER_OF_U_DISK_IS_0 => __('api.THE_REMAINING_AUTHORIZED_NUMBER_OF_U_DISK_IS_0'),
            self::PHYSICAL_SERIAL_NUMBER_IS_EMPTY => __('api.PHYSICAL_SERIAL_NUMBER_IS_EMPTY'),
            self::PHYSICAL_SERIAL_NUMBER_ERROR => __('api.PHYSICAL_SERIAL_NUMBER_ERROR'),
            self::LOGICAL_SERIAL_NUMBER_IS_EMPTY => __('api.LOGICAL_SERIAL_NUMBER_IS_EMPTY'),
            self::LOGICAL_SERIAL_NUMBER_ERROR => __('api.LOGICAL_SERIAL_NUMBER_ERROR'),
            self::INSUFFICIENT_NUMBER_OF_USB_FLASH_DRIVES => __('api.INSUFFICIENT_NUMBER_OF_USB_FLASH_DRIVES'),
            self::U_DISK_ENCRYPTION_NUMBER_HAS_REACHED_THE_MAXIMUM => __('api.U_DISK_ENCRYPTION_NUMBER_HAS_REACHED_THE_MAXIMUM'),
            self::U_DISK_ENCRYPTION_FAILED => __('api.U_DISK_ENCRYPTION_FAILED'),
            self::THE_SYSTEM_IS_BUSY_PLEASE_TRY_AGAIN_LATER => __('api.THE_SYSTEM_IS_BUSY_PLEASE_TRY_AGAIN_LATER'),
            self::BUSINESS_ID_IS_EMPTY => __('api.BUSINESS_ID_IS_EMPTY'),
            self::BUSINESS_ID_ERROR => __('api.BUSINESS_ID_ERROR'),
            self::THE_MERCHANT_DOES_NOT_EXIST => __('api.THE_MERCHANT_DOES_NOT_EXIST'),
            self::U_DISK_IS_DISABLED => __('api.U_DISK_IS_DISABLED'),
            self::U_DISK_IS_NOT_VALID => __('api.U_DISK_IS_NOT_VALID'),
            self::U_DISK_HAS_EXPIRED => __('api.U_DISK_HAS_EXPIRED'),
            self::U_DISK_RUNNING_TIMES_REACHED_THE_MAXIMUM => __('api.U_DISK_RUNNING_TIMES_REACHED_THE_MAXIMUM'),
            self::THE_MERCHANT_CONFIGURATION_IS_INCORRECT => __('api.THE_MERCHANT_CONFIGURATION_IS_INCORRECT'),
            self::SERVICE_EXCEPTION => __('api.SERVICE_EXCEPTION'),
            self::NO_UPDATE_STRATEGY => __('api.NO_UPDATE_STRATEGY'),
            self::EVENT_NAME_CANNOT_BE_EMPTY => __('api.EVENT_NAME_CANNOT_BE_EMPTY'),
            self::THE_MACHINE_CODE_CANNOT_BE_EMPTY => __('api.THE_MACHINE_CODE_CANNOT_BE_EMPTY'),
            self::U_DISK_CAPACITY_IS_EMPTY => __('api.U_DISK_CAPACITY_IS_EMPTY'),
            self::U_DISK_CAPACITY_ERROR => __('api.U_DISK_CAPACITY_ERROR'),
        ];

        if (isset($msgArray[$msgCode])) {
            $this->message = $msgArray[$msgCode];
            $this->code = $msgCode;
        } else {
            $this->message = $msgCode;
            $this->code = self::DEFAULT_ERROE;
        }
    }

}
