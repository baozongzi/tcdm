<?php

namespace app\api\controller;

use app\common\controller\Api;

use app\api\controller\Category;
use app\api\controller\Questions;
use app\api\controller\papers\Rule;
use app\api\model\Papers;
use app\api\model\PapersQuestions;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Allthedata extends Api
{


    public function getList()
    {
        $data = [];

        $category         = new Category();
        $data['category'] = $category->getChaptersCategoryList();

        $questions = new Questions();
        $data['questions'] = $questions->getAllList();

        $papers = new Rule();
        $data['papers'] = $papers->getPaperRuleList();

        $data['papersCategory'] = $category->getPaperCaetgory();

        $data['version'] = model('Config')->where('name', 'version')->value('value');
        $data['pdfversion'] = model('Config')->where('name', 'pdfversion')->value('value');


        return api_json('0', 'ok', $data);
    }

    /**
     * 生成版本数据文件
     * @return bool
     */
    public function versionData()
    {
        $data = [];

        $category         = new Category();
        $data['category'] = $category->getChaptersCategoryList();

        $questions = new Questions();
        $data['questions'] = $questions->getAllList();

        $papers = new Rule();
        $data['papers'] = $papers->getPaperRuleList();

        $data['papersCategory'] = $category->getPaperCaetgory();

        $data['version'] = model('Config')->where('name', 'version')->value('value');
        $data['pdfversion'] = model('Config')->where('name', 'pdfversion')->value('value');
        // 把PHP数组转成JSON字符串
        $json_string = json_encode($data);
        // 写入文件
        if(file_put_contents('versionData.json', $json_string)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取服务器当前版本号
     * @return mixed
     */
    public function getVersion()
    {
        $data['version'] = model('Config')->where('name', 'version')->value('value');
        $data['pdfversion'] = model('Config')->where('name', 'pdfversion')->value('value');
        return api_json('0', 'ok', $data);
    }

    /**
     * 保存前台提交更新数据
     * @return \think\response\Json
     */
    public function saveTest()
    {
        $data = [
            'uid'      => input('uid'),
            'data'   => input('data')
        ];
        if ($id = model('test')->where('uid', $data['uid'])->value('id')){
            if (model('test')->save(['data' => $data['data']], ['uid' => $data['uid']])){
                return api_json('0', '同步成功', null);
            }
        }else{
            if (model('test')->save($data)){
                return api_json('0', '同步成功', null);
            }
        }
        return api_json('1', '同步失败', null);
    }

    public function getTest()
    {
        $uid = input('uid');
        return api_json('0', 'ok', model('test')->where('uid', $uid)->value('data'));
    }

    /**
     * 安卓更新
     * @return \think\response\Json
     */
    public function updateAndroidApp()
    {
        $appversion = model('Config')->where('name', 'appversion')->value('value');
        $appdesc    = model('Config')->where('name', 'appdesc')->value('value');
        $appurl     = model('Config')->where('name', 'appurl')->value('value');
        $data   = [
            'version'   => $appversion,
            'desc'      => $appdesc,
            'url'       => $appurl
        ];
        return api_json(0,'ok', $data);
    }

    /**
     * 获取所有图片地址
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getImagesUrl()
    {
        $images = model('Attachment')->field('id,url')->where('mimetype', 'LIKE', 'image%')->select();
        return api_json(0,'ok', $images);
    }


}
