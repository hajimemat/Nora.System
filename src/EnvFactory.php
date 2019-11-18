<?php
declare(strict_types=1);
namespace Nora\System;

use Nora\DI\Module;

class EnvFactory
{
    public function __invoke(
        array $server = [],
        array $get = [],
        array $post = []
    ) : EnvInterface {
        $server = array_merge($_SERVER, $server);
        $get = array_merge($_GET ?? [], $get);
        $post = array_merge($_POST ?? [], $post);
        $env = new Env($server, $get, $post);
        return $env;
    }
}
