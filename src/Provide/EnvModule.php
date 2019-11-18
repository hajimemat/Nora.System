<?php
declare(strict_types=1);
namespace Nora\System\Provide;

use Nora\DI\Module;
use Nora\System\{
    EnvInterface,
    EnvFactory
};

class EnvModule extends Module
{
    public function configure()
    {
        $this
            ->bind(EnvFactory::class)
            ->to(EnvFactory::class);

        $this
            ->bind(EnvInterface::class)
            ->toProvider(EnvProvider::class);
    }
}
