<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use app\model\Cate;
use app\model\Track;
use app\model\TrackRecords;
use app\model\User;
use think\facade\View;
use think\facade\Request;


class TrackingRecord extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        return 123456;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return string
     */
    public function create($id,Track $track, Cate $cate, User $user)
    {
        //新增记录
        $tab = $track->find($id);
        $user_name = $user->where('status', '=', 1)->field('id,name')->select()->toArray();
        $cat = $cate->where('status', '=', 1)->where('cat', '=', 7)->field('id,name')->select()->toArray();
        $urlAdd = '/trackingrecord/save';
        View::assign([
            'tab'=>$tab,
            'cat' => $cat,
            'user_name' => $user_name,
            'urlAdd'=>$urlAdd
        ]);
        return View::fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(TrackRecords $trackRecords)
    {
        //
        $all = Request::param();
        if (isset($all['recou'])) {
            $all['recou']++;
        } else {
            $all['recou'] = 0;
        }
        $insert = $trackRecords->save($all);
        if ($insert) {
            return json_encode(['code' => 0]);
        } else {
            return json_encode(['code' => 1]);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @param User $user
     * @param Cate $cate
     * @param TrackRecords $trackRecords
     * @return string|\think\Response
     */
    public function edit(int $id,User $user, Cate $cate,TrackRecords $trackRecords)
    {
        //
        $tab = $trackRecords->find($id);
        $user_name = $user->where('status', '=', 1)->field('id,name')->select()->toArray();
        $cat = $cate->where('status', '=', 1)->where('cat', '=', 7)->field('id,name')->select()->toArray();
//        dump($tab->toArray());
        $urlUpdate='/trackingrecord/update';
        View::assign([
            'tab' => $tab,
            'user_name' => $user_name,
            'cat' => $cat,
            'urlUpdate'=>$urlUpdate
        ]);
        return View::fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param TrackRecords $trackRecords
     * @return false|string|\think\Response
     */
    public function update(TrackRecords $trackRecords)
    {
        //
        $tab = Request::param();
//        dump($tab);
        $insert = $trackRecords->inc('recou')->save($tab);
        if ($insert) {
            return json_encode(['code' => 0]);
        } else {
            return json_encode(['code' => 1]);
        }
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @param TrackRecords $trackRecords
     * @return false|string|\think\Response
     */
    public function delete(int $id, TrackRecords $trackRecords)
    {
        //
        $dbm = $trackRecords;
        $insert = $dbm->where('id', $id)->useSoftDelete('delete_time', date('y-m-d H:i:s', time()))->delete();
        if ($insert) {
            return json_encode(['code' => 0]);
        } else {
            return json_encode(['code' => 1]);
        }
    }
}
