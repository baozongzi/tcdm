<?php

namespace app\api\controller\general;

use app\common\controller\Api;

/**
 * 系统配置
 *
 * @icon fa fa-circle-o
 */
class Config extends Api
{

    protected $model = null;
    protected $noNeedRight = ['check'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Config');
    }

    /**
     * 获取配置接口
     * @return \think\response\Json
     */
    public function getConfigAll()
    {
        $website = model('Config')->where('name','website')->value('value');
        $res = $this->model->field('id,name,value')->select();
        foreach ($res as $k=>$v){
            if ($v['name'] == 'telephone'){
                $data['telephone'] = $v['value'];
            }elseif ($v['name'] == 'email'){
                $data['email'] = $v['value'];
            }elseif ($v['name'] == 'website'){
                $data['website'] = $v['value'];
            }elseif ($v['name'] == 'logo'){
                $data['logo'] = $website . $v['value'];
            }elseif ($v['name'] == 'wechat'){
                $data['wechat'] = $website  . '/api/general/config/detail/ids/'.$v['id'];
            }elseif ($v['name'] == 'about'){
                $data['about'] = $website  . '/api/general/config/detail/ids/'.$v['id'];
            }
        }
        return api_json('0', 'ok', $data);
    }

    public function detail($ids)
    {
        $content = $this->model->where('id', $ids)->value('value');
        $this->assign('content', $content);
        return $this->view->fetch();
    }

    /**
     * 获取用户注册协议接口
     */
    public function getConfigProtocol()
    {
        return api_json('0', 'ok', $this->model->where('name', 'protocol')->value('value'));
    }


}
