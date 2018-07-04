<?php

namespace app\api\controller;

use app\common\controller\Api;
use fast\Tree;

/**
 * 分类管理
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类
 */
class Category extends Api
{

    /**
     * Category模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Category');
    }


    /**
     * 章节练习接口
     * @return \think\response\Json
     */
    public function getChaptersList()
    {
        $ids = input('ids');

        $result = $this->model->getChaptersQuestionList($ids);

        return api_json('0', 'ok', $result);

    }

    /**
     * 获取所有章节练习科目
     * @return \think\response\Json
     */
    public function getChaptersCategoryList()
    {
        $ids = [
            ['33','19','44','25'],
            ['36','32','39'],
            ['35','31','40','45','28']
        ];
        foreach ($ids as $k=>$id){
            foreach ($id as $v){
                $result[] = $this->model->getChaptersQuestionList($v);
            }
        }

        return $result;
    }

    /**
     * 获取所有抽题科目
     * @return mixed
     */
    public function getPaperCaetgory()
    {
        $result = $this->model->field('id,pid,type,name,accounted')->where('status', 'normal')->order('weigh desc,id desc')->select();

        foreach ($result as $k=>$v){
            $v['ppid'] = $this->model->where('id', $v['pid'])->value('pid'); // 向上二级ID
        }

        return $result;
    }

    /**
     * 获取考前押题是否可用
     * @return \think\response\Json
     */
    public function getTestYaTiStatus()
    {
        $id     = input('id');
        $status = $this->model->where('id', $id)->value('status');

        if ($status == 'hidden'){
            return api_json('0', 'ok', model('Config')->where('name', 'closemsg')->value('value'));
        }
        return api_json('0', 'ok', '1');
    }

}
