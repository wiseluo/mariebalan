<?php

namespace app\api\service;

class BaseService
{
    public function __get($name)
    {
        return model('api/'.$name, 'repository');
    }
}