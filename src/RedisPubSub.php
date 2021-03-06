<?php

namespace hollisho\redischannel;
use hollisho\redischannel\lib\clients\RedisClient;
use hollisho\redischannel\lib\exceptions\RedisException;


/**
 * Class RedisPubSub
 * @package hollisho\redischannel
 * @author Hollis Ho
 */
class RedisPubSub
{
    /* @var $_client \Redis */
    private $_client;

    public function __construct(\Redis $redis)
    {
        $this->_client = $redis;
    }

    public function __call($name, $params)
    {
        try {
            return call_user_func_array([$this->_client, $name], $params);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            throw new RedisException($e->getMessage());
        }
    }

    /**
     * 只能通过静态方法获得该类的对象(单例模式)
     * @author Hollis Ho <he_wenzhi@126.com>
     * @param array $options
     * @return null|RedisPubSub
     */
    public static function getInstance($options = [])
    {
        static $obj = null;
        if ($obj == null) {
            $redis = RedisClient::getInstance($options);
            $obj = new self($redis);
        }
        return $obj;
    }

    /**
     * 发布消息
     * @param string $channel
     * @param string $message
     */
    public function publish($channel, $message)
    {
        $this->_client->publish($channel, $message);
    }

    /**
     * 订阅消息
     * @param array $channelArr
     * @param array $callback
     */
    public function subscribe($channelArr = [], $callback)
    {
        $this->_client->subscribe($channelArr, $callback);
    }
}
