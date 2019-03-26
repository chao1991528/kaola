<?php

namespace Org\Cache;
/**
 * Redis操作类
 *
 */
class CacheRedis
{
    private $ip, $port, $pwd, $timeout;

    /**
     * @var \Redis
     */
    private $oRedis;

    public function __construct($ip, $port, $pwd, $timeout)
    {
        $this->ip       = $ip;
        $this->port     = $port;
        $this->timeout  = $timeout;
        $this->pwd      = $pwd;
    }


    private $connected = FALSE;


    protected function log(\Exception $ex)
    {
        $content = "ip:{$this->ip},port:{$this->port}-";
        $content .= $ex->getTraceAsString() . ',msg:' . $ex->getMessage();
        \think\Log::log($content);
    }


    private function connect()
    {
        if ($this->connected) {
            return TRUE;
        }
        if (!$this->oRedis) {
            $this->oRedis = new \Redis();
        }
        try {
            $ret = $this->oRedis->connect($this->ip, $this->port, $this->timeout);
            if (!$ret) {
                return FALSE;
            }
            if ($this->pwd) {
                $this->oRedis->auth((string) $this->pwd);
            }
            /*
            $this->oRedis->setOption(\Redis::OPT_SERIALIZER,
                $this->serializer ? \Redis::SERIALIZER_PHP : \Redis::SERIALIZER_NONE);
            */
            $this->connected = TRUE;
            return TRUE;
        } catch (\Exception $e) {
            //var_dump($e);
        }
        return FALSE;
    }


    public function setBit($key, $offset, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->setBit($key, $offset, $value ? 1 : 0);
            } catch (\Exception $ex) {
                $this->connected = FALSE;
                $this->log($ex);
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function getBit($key, $offset, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->getSet($key, $offset);
            } catch (\Exception $ex) {
                $this->connected = FALSE;
                $this->log($ex);
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * List章节 无索引序列 把元素加入到队列左边(头部).如果不存在则创建一个队列.返回该队列当前元素个数/false
     * 注意对值的匹配要考虑到serialize.array(1,2)和array(2,1)是不同的值
     * @param String $key
     * @param Mixed $value
     * @return false/Int. 如果连接不上或者该key已经存在且不是一个队列
     */
    public function lPush($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->lPush($key, $value);
            } catch (\Exception $ex) {
                $this->connected = FALSE;
                $this->log($ex);
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function rPush($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->rPush($key, $value);
            } catch (\Exception $ex) {
                $this->connected = FALSE;
                $this->log($ex);
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function lPop($key, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->lPop($key);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function lLen($key, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->lLen($key);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function rPop($key, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->rPop($key);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function lRange($key, $start, $end, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->lRange($key, $start, $end);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function lRem($key, $value, $count, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->lRem($key, $value, $count);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function lTrim($key, $start, $stop, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->lTrim($key, $start, $stop);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 有序集合添加
     * @param string $key
     * @param string $index
     * @param string $value
     * @return bool|mixed
     */
    public function zAdd($key, $score, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->zAdd($key, $score, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 获取指定条件下的集合
     * @param String $key
     * @param int $start 最小索引值
     * @param int $end 最大索引值
     * @return bool|mixed
     */
    public function zRange($key, $start, $end, $withScores = TRUE, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->zRange($key, $start, $end, $withScores);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 从大到小获取有序集合数据
     * @param String $key
     * @param int $start 最小索引值
     * @param int $end 最大索引值
     * @return bool|mixed
     */
    public function zRevRange($key, $start, $end, $withScores = TRUE, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->zRevRange($key, $start, $end, $withScores);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 获取有序集合数量
     * @param string $key
     * @param int $start
     * @param int $end
     * @return boolean
     */
    public function zCount($key, $start, $end, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->zCount($key, $start, $end);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 移除有序集合中的一个或多个成员
     * @param string $key
     * @param string $hash
     * @return boolean
     */
    public function zRem($key, $hash, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    return FALSE;
                }
                return $this->oRedis->zRem($key, $hash);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 设置或替换Hash.
     * @param String $key
     * @param String $hashKey
     * @param Mixed $value
     * @return Boolean
     */
    public function hSet($key, $hashKey, $value)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hSet($key, $hashKey, $value);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    /**
     * 获取单个.失败或不存在为false
     * @param String $key
     * @param String $hashKey
     * @return Mixed
     */
    public function hGet($key, $hashKey)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hGet($key, $hashKey);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    /**
     * 批量获取.key不存在的对应的值为false
     * @param String $key
     * @param array $memberKeys
     * @return array|bool
     */
    public function hMget($key, $memberKeys)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hMget($key, $memberKeys);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }

    public function hGetAll($key)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hGetAll($key);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    /**
     * 批量设置
     * @param String $key
     * @param array $members 键值对
     * @return Boolean
     */
    public function hMset($key, $members)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hMset($key, $members);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    /**
     * 删除.成功为true,否则false
     * @param String $hashKey 大hash Key
     * @param String $key
     * @return Boolean
     */
    public function hDel($hashKey, $key)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hDel($hashKey, $key);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    /**
     * 设置.有则覆盖.true成功false失败
     * @param String $key
     * @param Mixed $value
     * @return Boolean
     */
    public function set($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->set($key, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 分布式锁函数
     * @param $key string key
     * @param $value int 值
     * @param $expire int 锁自动过期时间
     */
    public function distributedLock($key, $value, $expire, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->rawCommand("set", $key, $value, "nx", "ex", $expire);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;

    }


    /**
     * 获取.不存在则返回false
     * @param String $key
     * @return false/Mixed
     */
    public function get($key, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->get($key);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 先获取该key的值,然后以新值替换掉该key.该key不存在则添加同时返回false
     * @param String $key
     * @param Mixed $value
     * @return Mixed/false
     */
    public function getSet($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->getSet($key, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 给该key添加一个唯一值.相当于制作一个没有重复值的数组
     * @param String $key
     * @param Mixed $value
     * @return false/int 该值存在或者该键不是一个集合返回0,连接失败为false,否则为添加成功的个数1
     */
    public function sAdd($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->sAdd($key, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 删除该集合中对应的值
     * @param String $key
     * @param String $value
     * @return Boolean 没有该值返回false
     */
    public function sRemove($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->sRemove($key, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 把某个值从一个key转移到另一个key
     * @param String $srcKey
     * @param String $dstKey
     * @param Mixed $value
     * @return Boolean 源key不存在/目的key不存在/源值不存在->false
     */
    public function sMove($srcKey, $dstKey, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->sMove($srcKey, $dstKey, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 返回所给key列表所有的值,相当于求并集
     * @param Array $keys
     * @return Boolean/Array
     */
    public function sUnion($keys, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->sUnion($keys);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 返回所给key列表所有的值,
     * @param Array $keys
     * @return Boolean/Array
     */
    public function sMembers($key, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->sMembers($key);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 删除某key/某些key
     * @param String /Array $keys
     * @return Boolean/int 被真实删除的个数
     */
    public function delete($keys, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->delete($keys);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function incr($key, $by = 0)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $by ? $this->oRedis->incrBy($key, $by) : $this->oRedis->incr($key);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    public function hIncrBy($key, $hashKey, $by)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hIncrBy($key, $hashKey, $by);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    public function setnx($key, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->setnx($key, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    /**
     * 设置或替换Hash.
     * @param String $key
     * @param String $hashKey
     * @param Mixed $value
     * @return Boolean
     */
    public function hSetNx($key, $hashKey, $value)
    {
        try {
            if (!$this->connect()) {
                return FALSE;
            }
            return $this->oRedis->hSetNx($key, $hashKey, $value);
        } catch (\Exception $ex) {
            $this->log($ex);
            $this->connected = FALSE;
        }
        return FALSE;
    }


    public function setex($key, $time, $value, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->setex($key, $time, $value);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function exists($key, $try_cnt = 1)
    {
        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->exists($key);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function hExists($key, $hashKey, $try_cnt = 1)
    {

        do {
            $try_cnt--;
            try {
                if (!$this->connect()) {
                    continue;
                }
                return $this->oRedis->hExists($key, $hashKey);
            } catch (\Exception $ex) {
                $this->log($ex);
                $this->connected = FALSE;
            }
        } while ($try_cnt > 0);
        return FALSE;
    }


    public function multi($pipline = FALSE)
    {
        try {
            return $pipline == TRUE ? $this->oRedis->multi(\Redis::PIPELINE) : $this->oRedis->multi();
        } catch (\Exception $ex) {
            $this->log($ex);
        }
    }


    public function exec()
    {
        try {
            return $this->oRedis->exec();
        } catch (\Exception $ex) {
            $this->log($ex);
        }
    }


    public function expire($key, $expire)
    {
        try {
            return $this->oRedis->expire($key, $expire);
        } catch (\Exception $ex) {
            $this->log($ex);
        }
    }

}
