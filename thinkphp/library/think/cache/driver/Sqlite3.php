<?php
// +----------------------------------------------------------------------
// | Author: laotan <271513820@qq.com>
// +----------------------------------------------------------------------

namespace think\cache\driver;

use think\cache\Driver;

/**
 * Sqlite缓存驱动
 * @author    liu21st <liu21st@gmail.com>
 */
class Sqlite3 extends Driver
{
		protected $db;
    protected $options = [
        'db'         => ':memory:',
        'table'      => 'sharedmemory',
        'prefix'     => '',
        'expire'     => 0,
        'persistent' => false,
    ];

    /**
     * 构造函数
     * @param array $options 缓存参数
     * @throws \BadFunctionCallException
     * @access public
     */
    public function __construct($options = [])
    {
        if (!extension_loaded('sqlite3')) {
            throw new \BadFunctionCallException('not support: sqlite');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
				$this->db = new \SQLite3($this->options['db']);
    }

    /**
     * 获取实际的缓存标识
     * @access public
     * @param string $name 缓存名
     * @return string
     */
    protected function getCacheKey($name)
    {
        return $this->options['prefix'] . $this->db->escapeString($name);
    }

    /**
     * 判断缓存
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name)
    {
        $name   = $this->getCacheKey($name);
        $sql    = 'SELECT value FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\' AND (expire=0 OR expire >' . $_SERVER['REQUEST_TIME'] . ') LIMIT 1';
        $result = $this->db->query($sql);
        return $result->numColumns();
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $name   = $this->getCacheKey($name);
        $sql    = 'SELECT value FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\' AND (expire=0 OR expire >' . $_SERVER['REQUEST_TIME'] . ') LIMIT 1';
        $result = $this->db->query($sql);//SQLite3Result
				$row = $result->fetchArray(SQLITE3_NUM);
        if ($row) {
						$content = $row[0];//只取第一条 $result->fetchArray(SQLITE3_ASSOC)
            if (function_exists('gzcompress')) {
                //启用数据压缩
                $content = gzuncompress(base64_decode($content));
            }
            return unserialize($content);
        }
        return $default;
    }

    /**
     * 写入缓存
     * @access public
     * @param string            $name 缓存变量名
     * @param mixed             $value  存储数据
     * @param integer|\DateTime $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        $name  = $this->getCacheKey($name);
        $value = $this->db->escapeString(serialize($value));
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp();
        } else {
            $expire = (0 == $expire) ? 0 : (time() + $expire); //缓存有效期为0表示永久缓存
        }
        if (function_exists('gzcompress')) {
            //数据压缩
            $value = gzcompress($value, 3);
        }
        if ($this->tag) {
            $tag  = $this->tag;
            $this->tag = null;
        } else {
            $tag = '';
        }
        $sql = 'REPLACE INTO ' . $this->options['table'] . ' (var, value, expire, tag) VALUES (\'' . $name . '\', \'' . base64_encode($value) . '\', \'' . $expire . '\', \'' . $tag . '\')';
        if ($this->db->query($sql)) {
            return true;
        }
        return false;
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string    $name 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        if ($this->has($name)) {
            $value = $this->get($name) + $step;
        } else {
            $value = $step;
        }
        return $this->set($name, $value, 0) ? $value : false;
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string    $name 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        if ($this->has($name)) {
            $value = $this->get($name) - $step;
        } else {
            $value = -$step;
        }
        return $this->set($name, $value, 0) ? $value : false;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name)
    {
        $name = $this->getCacheKey($name);
        $sql  = 'DELETE FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\'';
        $this->db->query($sql);
        return true;
    }

    /**
     * 清除缓存
     * @access public
     * @param string $tag 标签名
     * @return boolean
     */
    public function clear($tag = null)
    {
        if ($tag) {
            $name = $this->db->escapeString($tag);
            $sql  = 'DELETE FROM ' . $this->options['table'] . ' WHERE tag=\'' . $name . '\'';
            $this->db->query($sql);
            return true;
        }
        $sql = 'DELETE FROM ' . $this->options['table'];
        $this->db->query($sql);
				//$this->db->close();
        return true;
    }
}
