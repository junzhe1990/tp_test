<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @property int $id
 * @property int $isok 是否正常
 * @property int $recou
 * @property int $track_id 追踪项目ID
 * @property string $check_time 核查时间
 * @property string $content 核查结果备注
 * @property string $create_time
 * @property string $delete_time
 * @property string $update_time
 * @property string $user_name
 * @mixin \think\Model
 */
class TrackRecords extends Model
{
    //
    use SoftDelete;
    protected $defaultSoftDelete = null;

    public function getIsokAttr($value)
    {
        return $value == 1 ? '正常' : '异常';
    }

    public function getDotAttr($value,$data)
    {
        $dot =[
            2=>'warning',
            1=>'gray'
        ];
        return $dot[$data['isok']];
    }
    public function getDaysAttr($value, $data)
    {
        $day_start = strtotime($data['update_time']);
        return  $days = round((time()-$day_start)/3600/24).'天';
    }

    public function getRecouAttr($value)
    {
        if ($value == 0){
            return '首次新增';
        }else{
            return $value.'次修改';
        }
    }

}
