<?php
namespace Nora\FileSystem\ValueObject;

use Nora\ValueObject\ValueObjectInterface;

/**
 * パス文字列
 */
class Path implements ValueObjectInterface
{
    /** すでに存在する場合は無視 **/
    const OPT_OVERWRITE_IGNORE = 0b0000001;
    /** 上書き可能 **/
    const OPT_OVERWRITE_ALLOW = 0b0000010;
    /** 書き込み必須 **/
    const OPT_FORCE_WRITABLE = 0b0000100;
    /** デフォルト **/
    const OPT_DEFAULT = 0b10;

    /** @var string **/
    protected $value;
    /** @var int **/
    protected $options = [];

    /**
     * @param string $path
     * @param array $options
     */
    public function __construct(string $path, int $options = self::OPT_DEFAULT)
    {
        $this->options = $options;
        $this->value = $this->validated($path);
    }

    /**
     * バリデーション
     */
    protected function validated($path)
    {
        return $path;
    }

    /**
     * 存在するパスかチェック
     */
    public function isExists() : bool
    {
        return file_exists($this->value);
    }

    /**
     * 書き込み可能かチェック
     */
    public function isWritable() : bool
    {
        return is_writable($this->value);
    }

    /**
     * 書き込み可能かチェック
     */
    public function isReadable() : bool
    {
        return is_readable($this->value);
    }

    /**
     * パスを文字列化する
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * パスを追加する
     */
    public function add(string $path, int $options = self::OPT_DEFAULT) : self
    {
        return $this->addArray([$path], $options);
    }

    /**
     * ディレクトリを一つ遡る
     */
    public function dirname()
    {
        return new static(dirname($this->value));
    }

    /**
     * パスを追加する
     */
    public function addArray(array $path, int $options = self::OPT_DEFAULT) : self
    {
        return new static(rtrim($this->value,"/") . "/" . implode("/", $path), $options);
    }

    /**
     * パスを追加する
     */
    public function __invoke(...$path)
    {
        return $this->addArray($path);
    }

    public function __get($name)
    {
        return $this->add($name);
    }
}
