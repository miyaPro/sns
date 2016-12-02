<?php
/**
 * Created by PhpStorm.
 * User: le.van.hai
 * Date: 12/2/2016
 * Time: 9:43 AM
 */

namespace App\Repositories;
use App\Notifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class NotificationRepository extends BaseRepository
{
    public function __construct(Notifications $notify)
    {
        Log::info('save construct notify');
        $this->model = $notify;
    }

    public function update($notify, $inputs){

    }

    public function store($inputs){
        Log::info('save store notify');
        $notify = new $this->model;
        $notify->type = $inputs['type'];
        $notify->notifiable_id = $inputs['notifiable_id'];
        $notify->notifiable_type = $inputs['notifiable_type'];
        $notify->read_at = $inputs['read_at'];
        $notify->data = $inputs['data'];
        $notify = $notify->save();
        return $notify;
    }
}