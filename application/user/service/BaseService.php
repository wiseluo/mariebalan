<?php

namespace app\user\service;

class BaseService
{
    public function __get($name)
    {
        return model('user/'.$name, 'repository');
    }
}