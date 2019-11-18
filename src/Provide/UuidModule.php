<?php
declare(strict_types=1);
namespace Nora\System\Provide;

use Nora\DI\Module;

use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidFactory;

class UuidModule extends Module
{
    public function configure()
    {
        $this
            ->bind(UuidFactoryInterface::class)
            ->to(UuidFactory::class);
    }
}
