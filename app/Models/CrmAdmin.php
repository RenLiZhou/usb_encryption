<?php

namespace App\Models;

use App\Exceptions\OrException;
use App\Jobs\ProcessCrmAdminLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authuser;
use Illuminate\Support\Facades\Route;

class CrmAdmin extends Authuser
{
    protected $fillable = ['username', 'password', 'email', 'status'];
    protected $hidden = ['password', 'remember_token'];
    protected $rulesCacheKey = 'crm_rules_cache_';
    protected $menuCacheKey = 'crm_menu_cache_';

    protected $pwdCost = 12;

    const ACTIVE       = 1; //启用
    const NOT_ACTIVE   = 0; //未启用

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('exist', function(Builder $builder) {
            $builder->where('status', '>=', 0);
        });
    }

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

    public function roles()
    {
        return $this->belongsToMany(CrmRole::class, CrmAdminRole::class, 'admin_id', 'role_id');
    }

    public function isUnique(string $field, $val)
    {
        $has = $this->where($field, $val)->count();
        return ($has === 0) ? true : false;
    }

    public function getAdminRules($isNew = false)
    {
        $key = $this->rulesCacheKey.$this->id;
        if ($isNew || !Cache::has($key)) $this->cacheRules($this->id);
        return Cache::get($key);
    }

    public function cacheRules(int $adminId)
    {
        $key = $this->rulesCacheKey.$adminId;
        $rules = $this->getRules($adminId);
        Cache::put($key, $rules);
    }

    /**
     * CRM管理员登录
     */
    public function auth(string $username, string $password, bool $remember)
    {
        $admin = CrmAdmin::where('username', $username)->first();
        if (null === $admin)
            throw new OrException('用户名或密码错误');
        if (CrmAdmin::NOT_ACTIVE === $admin->status)
            throw new OrException('该用户未启用');
        $res = password_verify($password, $admin->password);
        if ($res === false)
            throw new OrException('用户名或密码错误');

        Auth::guard('crm')->login($admin, $remember);
        (new CrmAdmin())->cacheRules($admin->id);

        $request = request();
        $data = [
            'route_name' => Route::currentRouteName(),
            'ip' => $request->getClientIp(),
            'url' => $request->path(),
            'method' => $request->getMethod(),
            'param' => json_encode($request->all())
        ];
        ProcessCrmAdminLog::dispatch(CrmAdminLog::TYPE_LOGIN, $admin->id, $data);
    }

    /**
     * CRM修改管理员密码
     */
    public function updatePwd(string $password, CrmAdmin $admin) : bool
    {
        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => $this->pwdCost]);
        if ($password === $admin->password) return true;
        $admin->password = $password;
        return $admin->save();
    }

    /**
     * CRM创建管理员
     */
    public function createAdmin(array $params) : bool
    {
        $adminModel = new CrmAdmin();
        $res = $adminModel->isUnique('email', $params['email']);
        if (!$res){
            throw new OrException('该邮箱已被使用');
        }
        $res = $adminModel->isUnique('username', $params['username']);
        if (!$res){
            throw new OrException('该用户名已被占用');
        }

        $data = [
            'role_id' => $params['role_id'],//后面同步关联
            'username' => $params['username'],
            'email' => $params['email'],
            'status' => CrmAdmin::ACTIVE,
            'password' => password_hash($params['password'], PASSWORD_DEFAULT, ['cost' => $this->pwdCost])
        ];

        $admin = CrmAdmin::create($data);
        if ($admin) {
            $admin->roles()->sync($params['role_id']);
            return true;
        }
        return false;
    }

    /**
     * CRM修改管理员信息
     */
    public function updateAdmin(array $params, CrmAdmin $admin) : bool
    {
        if ($admin->username != $params['username']) {
            $res = $admin->isUnique('username', $params['username']);
            if (!$res){
                throw new OrException('该用户名已被占用');
            }
        }
        if ($params['email'] && ($params['email'] != $admin->email)) {
            $res = $admin->isUnique('email', $params['email']);
            if (!$res){
                throw new OrException('该邮箱已被占用');
            }
        }

        $data = [
            'username' => $params['username'],
            'email' => $params['email']
        ];
        $res = $admin->update($data);
        if ($res) {
            $admin->roles()->sync($params['role_id']);
            return true;
        }
        return false;
    }

    public function getRules(int $adminId)
    {
        // 获取该用户拥有的需要认证的权限
        $rules = DB::table('crm_admin_role as ur')
            ->leftJoin('crm_role_rule as rl', 'ur.role_id', '=', 'rl.role_id')
            ->leftJoin('crm_rules as r', 'rl.rule_id', '=', 'r.id')
            ->where('ur.admin_id', $adminId)
            ->where('r.check', 1)
            ->where('r.rule', '<>', '')
            ->distinct()
            ->pluck('r.rule')
            ->toArray();
        // 获取不需要认证的权限
        $suRules = CrmRule::where('check', 0)->where('rule', '<>', '')->distinct()->pluck('rule')->toArray();
        return array_merge($rules, $suRules);
    }

    public function getAdminMenu($isNew = false)
    {
        $key = $this->menuCacheKey.$this->id;
        if ($isNew || !Cache::has($key)) $this->cacheMenu($this->id);
        return Cache::get($key);
    }

    public function cacheMenu(int $adminId)
    {
        $key = $this->menuCacheKey.$adminId;
        $menu = $this->getMenu($adminId);
        Cache::put($key, $menu);
    }

    public function getMenu(int $adminId)
    {
        // 获取该用户拥有的需要认证的菜单
        $menu = DB::table('crm_admin_role as ur')
            ->leftJoin('crm_role_rule as rl', 'ur.role_id', '=', 'rl.role_id')
            ->leftJoin('crm_rules as r', 'rl.rule_id', '=', 'r.id')
            ->where('ur.admin_id', $adminId)
            ->where('r.check', 1)
            ->where('r.type', 1)
            ->select('r.id', 'r.title', 'r.href', 'r.rule', 'r.pid', 'r.icon', 'r.sort', 'r.level')
            ->get();
        $menu = json_decode(json_encode($menu), true);
        // 获取不需要认证的菜单
        $suMenu = CrmRule::where('check', 0)->where('type', 1)
            ->select('id', 'title', 'href', 'rule', 'pid', 'icon', 'sort', 'level')->get()->toArray();
        $menu = array_merge($suMenu, $menu);
        usort($menu, function ($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });
        return $menu;
    }

    public function cleanAdminData()
    {
        $admin_id = Auth::guard('crm')->id();
        Cache::forget($this->rulesCacheKey.$admin_id);
        Cache::forget($this->menuCacheKey.$admin_id);
    }
}
