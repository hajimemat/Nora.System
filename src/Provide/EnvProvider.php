<?php
declare(strict_types=1);

namespace Nora\System\Provide;


use Nora\DI\ProviderInterface;

use Nora\App\Extension;
use Nora\App\Meta;
use Nora\App\Configuration\{
    ConfigureFactory,
    DefineConstants
};

use Psr\SimpleCache\CacheInterface;

use Nora\System\EnvFactory;


class EnvProvider implements ProviderInterface
{
    private $factory;

    public function __construct(EnvFactory $factory)
    {
        $this->factory = $factory;
    }

    public function get( )
    {
        return ($this->factory)();
    }
}
