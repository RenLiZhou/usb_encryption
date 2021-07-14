<?php

namespace App\Jobs;

use App\Models\CrmAdminLog;
use App\Models\CrmRule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCrmAdminLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $type;
    private $data;
    private $admin_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type ,$admin_id ,$data)
    {
        $this->admin_id = $admin_id;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == CrmAdminLog::TYPE_LOGIN){
            $route_title = '用户登录';
        }else{
            $route_name = $this->data['route_name'];
            $rule = CrmRule::query()->where('rule',$route_name)->select('title')->first();
            $route_title = !empty($rule->title)?$rule->title:'';
        }

        $logData = [
            'admin_id' => $this->admin_id,
            'type' => $this->type,
            'title' => $route_title,
            'ip' => $this->data['ip'],
            'url' => $this->data['url'],
            'method' => $this->data['method'],
            'param' => $this->data['param'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        CrmAdminLog::insert($logData);
    }
}
