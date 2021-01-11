<?php

declare(strict_types=1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @property int $id 栏目ID
 * @property int $pid 上级栏目ID
 * @property int $type 分类
 * 1：列表栏目；2：单页栏目
 * @property string $cate_name 栏目名称
 * @property string $create_time 添加时间
 * @property string $delete_time 删除时间
 * @property string $update_time 更新时间
 * @mixin \think\Model
 */
class Cate extends Model
{
    //
    use SoftDelete;

    protected $delete_time = 'delete_time';
    protected $update_time = 'update_time';
    protected $defaultSoftDelete = null;

    public function cateTree()
    {
        $cateTree = $this ->select();
        return $this->sort($cateTree);
    }

    public function sort($data,$pid=0,$level=0)
    {
        static $arr =[];
        foreach ($data as $k =>$v){
            if ($v['pid']==$pid){
                $v['level']= $level;
                $arr[] = $v;
                $this->sort($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }
}
