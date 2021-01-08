<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';
    
}
