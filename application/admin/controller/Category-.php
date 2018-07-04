<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\model\Category as CategoryModel;
use fast\Tree;

/**
 * 分类管理
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类
 */
class Category extends Backend
{

    protected $model = null;
    protected $categorylist = [];
    protected $noNeedRight = ['selectpage'];

    /**
     * 搜索字段
     */
    protected $searchFields = 'id,pid,type,name,accounted';

    public function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags']);
        $this->model = model('Category');

        $tree = Tree::instance();
        $tree->init($this->model->order('weigh desc,id desc')->select(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v)
        {
            $categorydata[$v['id']] = $v;
        }
        $this->view->assign("typeList", CategoryModel::getTypeList());
        $this->view->assign("parentList", $categorydata);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $search = $this->request->request("search");
            //构造父类select列表选项数据
            $list = [];
            if ($search)
            {
                foreach ($this->categorylist as $k => $v)
                {
                    if (stripos($v['name'], $search) !== false)
                    {
                        $list[] = $v;
                    }
                }
            }
            else
            {
                $list = $this->categorylist;
            }
            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                if ($this->dataLimit)
                {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    // 找pid值
                    foreach ($params['pid'] as $k=>$v){
                        if ($v == ''){
                            $params['pid'] = $params['pid'][$k-1];
                        }
                    }
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * Selectpage搜索
     * 
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }

    /**
     * 读取科目树
     *
     * @internal
     */
    public function categoryTree()
    {
        $this->loadlang('category');
        $ids = input("ids");

        $categoryList    = collection($this->model->order('id', 'desc')->select())->toArray();

        //获取当前数据的所属科目
        $nowCategoryList = model('Questions')->get($ids);

        //当前所属科目ID集合
        $catgory_ids     = $ids ? explode(',', $nowCategoryList->category) : [];

        Tree::instance()->init($categoryList);

        $categoryList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');

        $nodelist = [];
        foreach ($categoryList as $k => $v)
        {
            $state = array('selected' => in_array($v['id'], $catgory_ids));
            $nodelist[] = array('id' => $v['id'], 'parent' => $v['pid'] ? $v['pid'] : '#', 'text' => __($v['name']), 'type' => 'menu', 'state' => $state);
        }

        $this->success('', null, $nodelist);

    }

    /**
     * 获取所有科目下拉列表
     */
    public function getCategoryTreeSelect()
    {
        $categoryList    = collection($this->model->field('id,pid,name,type')->order('id', 'desc')->select())->toArray();
        Tree::instance()->init($categoryList);
        $categoryList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');

        $searchlist = [];
        foreach ($categoryList as $key => $value)
        {
            $searchlist[] = ['id' => $value['id'], 'name' => $value['name']];
        }
        $data = ['searchlist' => $searchlist];
        $this->success('', null, $data);
    }

    /**
     * 根据传入id列表查询科目名称
     * @return \think\response\Json
     */
    public function getCategroyList()
    {
        $categorylist = input('categorylist');
        $categorylist = explode(',', $categorylist);

        foreach ($categorylist as $k=>$v){
            $res[] = $this->model->getCategoryName($v);
        }

        return json($res);
    }
}
