<?php

namespace app\api\model;

use think\Model;
use think\Db;

class ActivityUser extends Model
{
    // 表名
    protected $name = 'activity_user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
    ];
    

    



    public function getAddTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['add_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setAddTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    /**
     * 获取当前活动报名人数接口
     * @param null $aid
     * @return int|string
     */
    public function getUserNum($aid = null)
    {
        return $this->where('aid' ,$aid)->count();
    }

    /**
     * 判断当前用户是否已经报名当前活动
     * @param $uid
     * @param $aid
     * @return array|false|\PDOStatement|string|Model
     */
    public function toSignUpCheck($aid = null, $uid = null)
    {
        $where = [
            'uid' => $uid,
            'aid' => $aid
        ];
        $res = $this->where($where)->find();
        if ($res){
            return 1;
        }
        return 0;
    }

    /**
     * 处理活动列表数据 报名人数和活动链接
     * @param $data
     * @return mixed
     */
    public function checkUserActivity($data, $uid)
    {
        foreach ($data as $k => &$v){
            $v['usernum']  = $this->getUserNum($v['id']);
            $v['signup']   = $this->toSignUpCheck($v['id'], $uid);
            $v['url']      = 'http://fire.mcykj.com/api/activity/detail/ids/'.$v['id'];
        }
        return $data;
    }


    /**
     * 获取用户报名列表
     * @param null $uid  用户ID
     * @param $offset    开始行
     * @param $limit     结束行
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getUserActivity($uid = null, $offset = 1, $limit = 10)
    {
        return Db::table('fa_activity')
            ->alias('a')
            ->field('a.id, a.type, a.title, a.time, a.image, a.describe')
            ->join('fa_activity_user au', 'a.id = au.aid')
            ->limit($offset, $limit)
            ->select();
    }

}
