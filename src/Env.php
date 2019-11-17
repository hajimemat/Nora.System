<?php
namespace Nora\System;

/**
 * 環境変数管理クラス
 */
class Env implements EnvInterface, \ArrayAccess
{
    /**
     * @var array 環境変数
     */
    private $_SERVER = [];

    /**
     * @var array _GETの代用
     */
    private $_GET = [];

    /**
     * @var array _POSTの代用
     */
    private $_POST = [];

    /**
     * 環境変数の名称差分の吸収用辞書
     */
    private $fallbacks = [
        'REMOTE_ADDR' => [
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ],
        'HTTP_HOST' => [
            'HTTP_X_FORWARDED_HOST',
            'HTTP_HOST'
        ]
    ];

    public static function create(array $server = [], array $get = [], array $post = []) : self
    {
        $server = array_merge($_SERVER, $server);
        $get = array_merge($_GET ?? [], $get);
        $post = array_merge($_POST ?? [], $post);
        $env = new Env($server, $get, $post);
        return $env;
    }

    protected function __construct(array $server, array $get, array $post)
    {
        $this->_SERVER = $this->normalizeAssoc($server);
        $this->_GET = $get;
        $this->_POST = $post;
    }

    // Normalize

    /**
     * Normalize: Array
     *
     * @param array $data
     * @return array
     */
    private function normalizeAssoc(array $data) : array
    {
        $normalized = [];

        foreach (array_keys($data) as $key) {
            $normalized[$this->normalizeKey($key)] = $data[$key];
        }

        return $normalized;
    }

    /**
     * Normalize: String
     *
     * @param string $key
     * @return string
     */
    private function normalizeKey(string $key)
    {
        if (false !== strpos($string, '_')) {
            return strtoupper($string);
        }
        if (preg_match_all('/((?:^|[A-Z])[a-z]+)/', $string, $m)) {
            return implode('_', array_map('strtoupper', $m[1]));
        }

        return strtoupper($string);
    }

    // ArrayAccess Interface

    /**
     * @param string $name;
     * @param mixed $name;
     */
    public function offsetGet($name)
    {
        return $this->getEnv($name);
    }

    /**
     * @param string $name;
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->hasEnv($name);
    }

    /**
     * @param string $name;
     * @param mixed $value;
     */
    public function offsetSet($name, $value)
    {
        throw new \Exception("can't set value to this object");
    }

    /**
     * @param string $name;
     */
    public function offsetUnset($name)
    {
        $this->delEnv($name);
    }

    /**
     * @param string $name;
     */
    public function __get($name)
    {
        if ($this->hasEnv($name)) {
            return $this->getEnv($name);
        }
        throw new \Exception(sprintf("%s is not defined", $name));
    }

    /**
     * @param string $name;
     */
    public function getEnv($name)
    {
        $normalized_name = $this->normalizeKey($name);

        // フォールバック設定されていた場合
        if (isset($this->fallbacks[$normalized_name])) {
            foreach ($this->fallbacks[$normalized_name] as $name) {
                if (isset($this->_SERVER[$name])) {
                    return $this->_SERVER[$name];
                }
            }
            return null;
        }
        if (isset($this->_SERVER[$normalized_name])) {
            return $this->_SERVER[$normalized_name];
        }
        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasEnv($name) : bool
    {
        $normalized_name = $this->normalizeKey($name);

        // フォールバック設定されていた場合
        if (isset($this->fallbacks[$normalized_name])) {
            foreach ($this->fallbacks[$normalized_name] as $name) {
                if (isset($this->_SERVER[$name])) {
                    return true;
                }
            }
            return false;
        }
        if (isset($this->_SERVER[$normalized_name])) {
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     */
    public function delEnv($name)
    {
        $normalized_name = $this->normalizeKey($name);
        if ($this->hasEnv($normalized_name)) {
            unset($this->_SERVER[$normalized_name]);
        }
    }

    /**
     * Get Request
     *
     * @return array
     */
    public function get() : array
    {
        return $this->_GET;
    }

    /**
     * Post Request
     *
     * @return array
     */
    public function post() : array
    {
        return $this->_POST;
    }

    /**
     * Request
     *
     * @return array
     */
    public function request() : array
    {
        return array_merge($this->_GET, $this->_POST);
    }
}
