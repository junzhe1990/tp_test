<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @property int $id ID
 * @property string $create_time
 * @property string $delete_time
 * @property string $name 名字
 * @property string $pwd 密码
 * @property string $update_time
 * @property-read \app\model\Profile[] $profile
 * @mixin \think\Model
 */
class User extends Model
{
    //
    public function profile()
    {
        return $this->hasMany(Profile::class,'user_id','id');
    }
}
