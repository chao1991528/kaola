<?php

namespace Org\Cache;

use Org\Cache\CacheRedis;


/**
 * redis操作工厂类
 */
class RedisShard
{
    /**
     * 获取配置对应的redis操作类
     */
    public static function getRedis()
    {
        static $redisCaches = [];
        $config = array();
        $redisConfig = C('redis');
        $config['host']         = $redisConfig['REDIS_HOST'] ? : '127.0.0.1';
        $config['port']         = $redisConfig['REDIS_PORT'] ? : 6379;
        $config['timeout']      = $redisConfig['REDIS_TIMEOUT'] ? : 3;
        $config['password']     = $redisConfig['REDIS_AUTH'] ? : '';

        $key = $config['host'] . ':' . $config['port'];
        if (isset($redisCaches[$key])) {
            return $redisCaches[$key];
        }
        $redis = new CacheRedis($config['host'], $config['port'], $config['password'], $config['timeout']);
        $redisCaches[$key] = $redis;
        return $redis;
    }
}
