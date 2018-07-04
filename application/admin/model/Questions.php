<?php

namespace app\admin\model;

use think\Model;

class Questions extends Model
{
    // 表名
    protected $name = 'questions';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [];

    // 定义题目类型
    protected $questionsType = [0 => '单选题', 1 => '多选题', 2 => '判断题', 3 => '主观题'];

    //字段类型转换
    protected $type = ['options' => 'json', 'images' => 'json'];

    /**
     * 传入科目ID加,号分隔符
     * @param $value
     * @return string
     */
    public function setCategoryAttr($value)
    {
        return ','.$value.',';
    }

    /**
     * 获取科目 删除,号分隔符
     * @param $value
     * @return string
     */
    public function getCategoryAttr($value)
    {
        return ltrim(rtrim($value, ','), ',');
    }

    public function editCategoryAttr($value)
    {
        $value = ltrim(rtrim($value, ','), ',');
        return explode(',', $value);
    }

    public function editJsonAttr($value)
    {
        return json_decode($value, true);
    }

    public function editSaveCategoryAttr($value)
    {
        return implode(",", $value);
    }





    /**
     * 读取题目
     * @return array
     */
    public function getTypeList()
    {
        return $this->questionsType;
    }

    /**
     * 读取题目图片位置
     * @return array
     */
    public static function getImageList()
    {
        $imageList = [
            'title' => '题目图片',
            'A' => '选项A图片',
            'B' => '选项B图片',
            'C' => '选项C图片',
            'D' => '选项D图片',
            'E' => '选项E图片',
            'analysis' => '解析图片',
        ];
        return $imageList;
    }


    /**
     * 遍历图片位置
     * @param $list
     * @return mixed
     */
    public function imagesCheck($list)
    {
        foreach ($list as $k=>$v){
            $image = '';
            if($v['images']){
                foreach ($v['images'] as $key=>$value){
                    switch ($key){
                        case 'title':
                            if (!empty($value)){
                                $image .= '<span class="label label-warning">题干</span> ';
                            }
                            break;
                        case 'A':
                            if (!empty($value)){
                                $image .= '<span class="label label-info">答案A</span> ';
                            }
                            break;
                        case 'B':
                            if (!empty($value)){
                                $image .= '<span class="label label-info">答案B</span> ';
                            }
                            break;
                        case 'C':
                            if (!empty($value)){
                                $image .= '<span class="label label-info">答案C</span> ';
                            }
                            break;
                        case 'D':
                            if (!empty($value)){
                                $image .= '<span class="label label-info">答案D</span> ';
                            }
                            break;
                        case 'E':
                            if (!empty($value)){
                                $image .= '<span class="label label-info">答案E</span> ';
                            }
                            break;
                        case 'analysis':
                            if (!empty($value)){
                                $image .= '<span class="label label-success">解析</span> ';
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
            $v['images'] = $image;

        }
        return $list;
    }


    







}
