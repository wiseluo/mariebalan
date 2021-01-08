<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    'wechat' => [
        // APP 引用的 appid
        'appid' => 'wx732a1766b872e34a',
        // 公众号 APPID
        'app_id' => 'wxa9bead9d26ae96ba',
        // 小程序 APPID
        'miniapp_id' => 'wxa9bead9d26ae96ba',
        // 微信支付分配的微信商户号
        'mch_id' => '1600311942',
        // 微信支付异步通知地址
        'notify_url' => '',
        // 微信支付签名秘钥
        'key' => 'De440d71d69441b57fa1c52b51245dcA',
        // 客户端证书路径，退款时需要用到
        'cert_client' => '',
        // 客户端秘钥路径，退款时需要用到
        'cert_key' => '',
        'log' => [ // optional
            'file' => './logs/wechat.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        //'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ],

    'alipay' => [
        // 支付宝分配的 APPID
        'app_id' => '2021001170608021',
        // 'app_id' => '2016102700771822', //沙箱
        // 支付宝异步通知地址
        'notify_url' => '',
        // 支付成功后同步通知地址
        'return_url' => '',
        // 支付公钥（不是应用公钥），验证签名时使用
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuvITJ5XjBCZwcteJIVD9PPSoxkPpFp/GhXdFdPoNcCBjZJNNvXP7udeHiFUoNFFBKE26Eq0v7ZkkFKFqusFgoZz2ZukgSFL5re7fg6rZzLdk/lTqBiqAUx9hC1cFTKKcOuBKTVx3JJ0BoXQZ2VSkWBYDZQ5AW2TO+7yRAHy+9sw0UNUaM8t51gytqRgDOSAVGfdbN96gNOvok3YDEp7+uMiPN0X8/gC4zfRDPIlwwP4iqaEvuMMqzqCnfVdTc54EfdhHci7JCE0T94lzW4R1ytYqUmbPvTKvGASjqzSDTz91b7pnS41j4cNKV1KF59rLbl4rIWch410lEXNbkdu0owIDAQAB',
        // 应用私钥，签名时使用  加密方式： **RSA2**
        'private_key' => 'MIIEowIBAAKCAQEAv0xBvcP80MZjFbMH0KUuuwR9GqA6bV0Fv+pqUS+SuScYoKfAtuDt9gkXoWUjyuCC/9zTBlSD2zC41HZ3lk2asB1gGGbBd2BBa5AqA0cf5QC9/eL2BQVa0LV3MvsDYo0CJmBXxsgjgI2DKr57bpBJ7t7Ew+jcLdGI3ZrVuql5XWq80Lj0x/r4t891ovMwQE/hO5AuWtoreTol8qfyuth68J5x9WXWGH7VjgNNayPka4pKmQ6PTKSfwks67yd8GzyBrf3kUsce7Ryje+UCn5WXFtx2QBucj0l+CXyNH0EPsvhZFZIqrLpnoe7ElNJIni2xcSlteaaamqZ8I4ue+0N3hwIDAQABAoIBAEs7hEdLQqwguWsZQ6OMsvod482K4i+Me+xkFnfjS8LBW3AjSSkjALLYFJEGo7Lv5NUXVW6R4mFbofT8uj9EOee1RcuS7dcy5ceVpNAxMpvvVMj6b2K+Hl8iXsSP717csdHB5gNpRn0SKlmvG//5gvoMZjnd5GmolDkC4bdNW0ufNZFcKFw6Zirllcs5fXxUSGEZsEmxrAF0bSFEseGRe6sQ1PIWJ+r/dJ3OUUiplaHfiywS03yJU+Dz3nYOut0N8sK7ExwSQ2Ie5PBcjqb9kdAfSPlx/VaLvM5qnrb/aLkgwuz2QeqRDjUw5TzdT4Nq8meVYBJ5J0ETpK3D0DGTGTECgYEA9v3QvzzoV2P9lgh81tLzI2rBL3KPXCz81T7Ia92/q14HwBNcq92Rr7s2O2RhVM25MKvOgNmbEGkm7y8GOXlxrkQXAtBJTMO2kZuxVOGdDqAG7hjoq4Y/pRZdKB2fLmiYjo6gXfPgT8lPOOGbh/Eyc/WcseLXMGYTGYeAeNlfgLsCgYEAxkZsthsG2KiL4dwkniRXRYT7t8brYaHGGYVPtno3IeM8Sgoh22oSelYegYnmZP3aMGXZvH4nVVwLaTDuaql96RRigJO9krWLG4SHrXfsuaw9eXIewD3ZSLVjUlE10Tv6KU5CC6VqERwItOcELDKWtIxMUGYwPU7oY86sq+jEDaUCgYBVDYJc2HSJ43zntXSH5YyknZZ46FJFw5gtNrl9q5bdRDwXAJPg+yO4CBfcy+xYb59eC0vJQyYKuKsXonHaSN/Eyt3BskgLjznHWn3uZOLYrnK0ew9kQY6ZIuJhdhHiwVIHhUXkFJ8h2ojVZZtRbNWa6PFUsHC50eyx1d9/vhsNEQKBgFgv6JhSiwgAZz8M6CiCZ2KfVEoYKochKfgd6Cd0UmM7K4yO1yI9GnzZIvZgvF283rfaBS8mOR5pMxYVUmWUf1EU5P7lN1Myde3GU4ZfKaYnqqwCixTcLXF+Y++v+SzX7VtD2HJPn05+1oyHl76Eva0OHb9AxEHwf8IsoXZQsCqVAoGBAJ+5QnGxvqyhb77OrzEK5PLSgR+v/Ll4xHV6jWVJ0j5N/Y0MCAIIP+EL1gbEGiz4Me/DJqqxT0aaqcOq7+t3K1IKqmZXOi+9b6PyfpH6zH5ffBD5rw2mnWwAWUttubw0++bcaTRAQTz6SQb0cAliU8mIwo5+qqo66cFXJht1TL7/',

        // 使用公钥证书模式，请配置下面两个参数，同时修改ali_public_key为以.crt结尾的支付宝公钥证书路径，如（./cert/alipayCertPublicKey_RSA2.crt）
        // 'app_cert_public_key' => './cert/appCertPublicKey.crt', //应用公钥证书路径
        // 'alipay_root_cert' => './cert/alipayRootCert.crt', //支付宝根证书路径
        'log' => [ // optional
            'file' => './logs/alipay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        // 'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ],
];
