<?php

namespace app\api\model;

use think\Model;

class Category extends Model
{
    // 表名
    protected $name = 'category';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];

    public function pdfilelist()
    {
        return $this->hasMany('Pdfilelist', 'category', 'id');
    }

    /**
     * 获取章节练习下的科目和试题数量
     * @param $ids
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getChaptersQuestionList($ids)
    {
        $result = $this->field('id,pid,name')->where('pid', $ids)->order('weigh desc,id desc')->select();

        foreach ($result as $k=>$v){
            $where['category'] = ['like', '%,'.$v['id'].',%'];
            $where['status']   = 1;
            $v['quenum']   = model('Questions')->where($where)->count();

            $v['child'] = $this->field('id,pid,name')->where('pid', $v['id'])->select();
            foreach ($v['child'] as $key=>$value){
                $where['category'] = ['like', '%,'.$value['id'].',%'];
                $value['quenum'] = model('Questions')->where($where)->count();
                $value['category'] = model('Questions')->where($where)->value('category');

            }
        }

        return $result;
    }

}
