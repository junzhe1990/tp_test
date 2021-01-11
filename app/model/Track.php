<?php

declare(strict_types=1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @property int $id
 * @property int $recou 更新次数
 * @property int $status 状态
 * @property string $content 核查方法
 * @property string $create_time 添加时间
 * @property string $cycle 核查周期
 * @property string $delete_time 删除时间
 * @property string $item 追踪项目
 * @property string $liable 责任人
 * @property string $update_time 更新时间
 * @property string $user_name 贡献人
 * @property-read \app\model\TrackRecords[] $track_records
 * @mixin \think\Model
 */
class Track extends Model
{
    use SoftDelete;
    protected $defaultSoftDelete = null;
    //关联核查记录
    public function trackRecords(): \think\model\relation\HasMany
    {
        return $this->hasMany(TrackRecords::class,'track_id','id')->order('update_time','DESC');
    }
//    设置更新次数
    public function getRecouAttr($value)
    {
        $value = $value == 0 ? '首次新增' : $value . '次更新';
        return $value;
    }

    public function getDaysAttr($value, $data)
    {
        $day_start = strtotime($data['update_time']);
        return  $days = round((time()-$day_start)/3600/24).'天';
    }

    public function searchContentAttr($query,$value)
    {
        return $value ? $query->whereLike('content','%'.$value.'%'):'';
    }

    public function searchItemAttr($query,$value)
    {
        return $value ? $query->whereLike('item','%'.$value.'%'):'';
    }

    public function searchLiableAttr($query,$value)
    {
        return $value ? $query->where('liable','=',$value) : '';
    }

    public function searchCycleAttr($query,$value)
    {
        return $value ? $query->where('cycle','=',$value) : '';
    }

    public function getTestAttr($value,$data)
    {
        $res = TrackRecords::where('track_id','=',$data['id'])->max('update_time',false);
        return $days = round((time()-strtotime($res))/3600/24);
    }
}
