<?php

declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\Cate;
use app\model\Track;
use app\model\User;

//use think\Request;
use think\facade\Request;
use think\facade\View;

/**
 * Class Tracking
 * @package app\controller
 */
class Tracking extends BaseController
{
    /**
     * 显示资源列表
     *
     * @param Track $track
     * @param Cate $cate
     * @param User $user
     * @return string|\think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(Track $track, Cate $cate, User $user)
    {
        //
        $dbm = $track;
        $map = Request::param();
        if (empty($map)) {
            $map['item'] = '';
            $map['content'] = '';
            $map['liable'] = '';
            $map['cycle'] = '';
        };
        $cat = $cate->where('status', '=', 1)->where('cat', '=', 7)->field('id,name')->select()->toArray();
        $tab = $dbm->field('id,item,content,cycle,user_name,liable,recou,status,create_time,update_time,delete_time')->with(['trackRecords'])
            ->withSearch(['item', 'cycle', 'content', 'liable'], [
                'item' => Request::param('item'),
                'cycle' => Request::param('cycle'),
                'content' => Request::param('content'),
                'liable' => Request::param('liable')
            ])
            ->order('update_time', 'DESC')
            ->paginate([
                'list_rows' => 20,
                'query' => Request::param()
            ]);
//        halt($tab);
        $user_name = $user->where('status', '=', 1)->field('id,name')->select()->toArray();
        $cite = '追光者，有你更精彩';
        $recordAdd= '/trackingrecord/create?id=';
        $recordEdit= '/trackingrecord/edit?id=';
        $urlAdd = '/tracking/create';
        $urlEdit = '/tracking/read?id=';
        $urlDel = '/tracking/delete';
        $urlSearch = '/tracking';
        $titleAdd = '增加追踪项目--我奉献我快乐';
        $titleEdit = '维护追踪信息--感谢每一次更新';
        View::assign([
            'tab' => $tab,
            'cite' => $cite,
            'urlAdd' => $urlAdd,
            'urlEdit' => $urlEdit,
            'titleAdd' => $titleAdd,
            'titleEdit' => $titleEdit,
            'urlDel' => $urlDel,
            'cat' => $cat,
            'user_name' => $user_name,
            'urlSearch' => $urlSearch,
            'map' => $map,
            'recordAdd'=>$recordAdd,
            'recordEdit'=>$recordEdit
        ]);
        return View::fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @param User $user
     * @param Cate $cate
     * @return string|\think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create(User $user, Cate $cate)
    {
        //
        $urlAdd = '/tracking/save';
        $user_name = $user->where('status', '=', 1)->field('id,name')->select()->toArray();
        $cat = $cate->where('status', '=', 1)->where('cat', '=', 7)->field('id,name')->select()->toArray();
        View::assign([
            'user_name' => $user_name,
            'cat' => $cat,
            'urlAdd' => $urlAdd
        ]);
        return View::fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param Track $track
     * @return string|\think\Response
     */
    public function save(Track $track)
    {
        //保存
        $dbm = $track;
        $all = Request::param();
        if (isset($all['recou'])) {
            $all['recou']++;
        } else {
            $all['recou'] = 0;
        }
        if (isset($all['status'])) {
            $all['status'] = 1;
        }else{
            $all['status'] = 0;
        }
        $insert = $dbm->save($all);
        if ($insert) {
            return json_encode(['code' => 0]);
        } else {
            return json_encode(['code' => 1]);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @param Track $track
     * @param User $user
     * @param Cate $cate
     * @return string|\think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read(int $id, Track $track, User $user, Cate $cate)
    {
        //显示编辑页面
        $tab = $track->field(true)->find($id);
        $user_name = $user->where('status', '=', 1)->field('id,name')->select()->toArray();
        $cat = $cate->where('status', '=', 1)->where('cat', '=', 7)->field('id,name')->select()->toArray();
//        halt($tab);
        $urlAdd='/tracking/update';
        View::assign([
            'tab' => $tab,
            'user_name' => $user_name,
            'cat' => $cat,
            'urlAdd'=>$urlAdd
        ]);
        return View::fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param Track $track
     * @return false|string|\think\Response
     */
    public function update(Track $track)
    {
        //更新数据
        $tab = Request::param();
        $insert = $track->inc('recou')->save($tab);
        if ($insert) {
            return json_encode(['code' => 0]);
        } else {
            return json_encode(['code' => 1]);
        }
    }

    /**
     * 删除指定资源
     *
     * @param Track $track
     * @return \think\Response
     */
    public function delete(Track $track)
    {
        //删除追踪项目
        $dbm = $track;
        $id = Request::param('id');
        $insert = $dbm->where('id', $id)->useSoftDelete('delete_time', date('y-m-d H:i:s', time()))->delete();
        if ($insert) {
            return json_encode(['code' => 0]);
        } else {
            return json_encode(['code' => 1]);
        }
    }

    public function test(Track $track)
    {
        $res = $track->with('trackRecords')->find(4);
//        dump($res);
        $res->append(['nothing']);
        return $res->test;
    }
}
