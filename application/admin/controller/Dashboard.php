<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';

        $totaluser = model('User')->count();
        $categorycount = model('Category')->count();
        $attachmentcount = model('Attachment')->count();

        $questionstotal = model('Questions')->count();
        $pdftotal = model('Pdfilelist')->count();
        $activetotal = model('Activity')->count();
        $activetotalUser = model('ActivityUser')->count();

        $questionTrue = model('Questions')->where('status', 1)->count();
        $questionFalse = model('Questions')->where('status', 0)->count();

        $this->view->assign([
            'totaluser'        => $totaluser,
            'questionstotal'   => $questionstotal,
            'pdftotal'         => $pdftotal,
            'activetotal'      => $activetotal,
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'uploadmode'       => $uploadmode,
            'categorycount'    => $categorycount,
            'attachmentcount'  => $attachmentcount,
            'questionTrue'     => $questionTrue,
            'questionFalse'    => $questionFalse,
            'activetotalUser'  => $activetotalUser
        ]);

        return $this->view->fetch();
    }

}
