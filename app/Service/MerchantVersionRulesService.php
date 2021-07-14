<?php

namespace App\Service;

use App\Exceptions\OrException;
use App\Models\Merchant;
use App\Models\MerchantRule;
use App\Models\MerchantVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MerchantVersionRulesService{

    const CACHE_RULES_NAME = 'merchant_version';

    /**
     * 缓存版本权限
     */
    public static function cacheRules($is_update = false){
        if(!Cache::has(self::CACHE_RULES_NAME) || $is_update){
            $data = [];

            $not_validate_rules = MerchantRule::where('check', 0)->get()->toArray();
            $merchant_versions = MerchantVersion::query()->with('rules')->get()->toArray();
            foreach ($merchant_versions as $va){
                $version_rules = [];

                $merchant_version_rules = array_merge($va['rules'],$not_validate_rules);
                foreach ($merchant_version_rules as $vb){
                    if(isset($version_rules[$vb['id']])){
                        continue;
                    }
                    $version_rules[$vb['id']] = [
                        'id' => $vb['id'],
                        'title' => $vb['title_name'],
                        'pid' => $vb['pid'],
                        'href' => $vb['href'],
                        'icon' => $vb['icon'],
                        'sort' => $vb['sort'],
                        'level' => $vb['level']
                    ];
                }
                usort($version_rules, function ($a, $b) {
                    return $a['sort'] <=> $b['sort'];
                });

                $data[$va['id']] = $version_rules;
            }
            Cache::put(self::CACHE_RULES_NAME,$data);
        }
        return Cache::get(self::CACHE_RULES_NAME);
    }
}
