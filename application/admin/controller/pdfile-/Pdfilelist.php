<?php

namespace app\admin\controller\pdfile;

use app\common\controller\Backend;
use app\common\model\Category as CategoryModel;
use fast\Tree;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Pdfilelist extends Backend
{
    
    /**
     * Pdfilelist模型对象
     */
    protected $model = null;
    protected $categorylist = [];

    /**
     * 搜索字段
     */
    protected $searchFields = 'id,url,name,category,createtime,updatetime';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Pdfilelist');

        $tree = Tree::instance();
        $tree->init(model('Category')->order('id', 'desc')->select(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        foreach ($this->categorylist as $k => $v)
        {
            if ($v['type']=='3'){
                $categorydata[$v['id']] = $v;
            }
        }
        $this->view->assign("typeList", CategoryModel::getTypeList());
        $this->view->assign("parentList", $categorydata);

    }

    public function update($ids = null)
    {
        if ($this->model->save(['updatetime' => time()],['id' => $ids])){
            $this->success('更新成功');
        }else{
            $this->success('更新失败');
        }
    }
}
