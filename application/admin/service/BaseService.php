<?php

namespace app\admin\service;

class BaseService
{
    public function __get($name)
    {
        return model('admin/'.$name, 'repository');
    }
}