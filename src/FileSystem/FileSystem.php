<?php
namespace Nora\FileSystem;


/**
 * ファイルシステム用のハンドラ
 */
class FileSystem
{
    /**
     * @param string $path
     */
    public function createPath(string $path) : ValueObject\Path
    {
        return new ValueObject\Path($path);
    }
}
