<?php

namespace app\api\model;

use think\Model;

class Message extends Model
{
    // 表名
    protected $name = 'message';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'time_text'
    ];
    

    



    public function getTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['time'];
        return is_numeric($value) ? date("Y-m-d", $value) : $value;
    }

    protected function setTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    /**
     * 获取信息展示内容
     * @param $type
     * @return array
     */
    public function getMessageRuleList($type)
    {
        $data = $this->where('type', $type)->find()->getData();

        $text = [
            'text' => null,
            'type' => null
        ];

        if ($data['time_status'] == 'normal'){
            if ($data['time'] > time()){
                $text['text'] .= $data['time_start'];
                $text['text'] .= round(($data['time'] - time())/3600/24);
                $text['text'] .= $data['time_end'];
                $text['type'] = 1;
            }
        }elseif ($data['text_status'] == 'normal'){
            $text['text'] .= $data['text'];
            $text['type'] = 0;
        }
        return $text;
    }


}
