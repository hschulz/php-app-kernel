<?php

declare(strict_types=1);

namespace Hschulz\Kernel\Tests\Unit\Bundle;

use function file_put_contents;
use Hschulz\Config\JSONConfigurationManager;
use Hschulz\Event\Manager;
use Hschulz\Kernel\Bundle\AbstractBundle;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class AbstractBundleTest extends TestCase
{
    public function testCanSetName(): void
    {
        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $bundle->setName('Integration');

        $this->assertEquals('Integration', $bundle->getName());
    }

    public function testCanSetConfig(): void
    {
        vfsStream::setup('integration');

        $file = vfsStream::url('integration/config.json');

        file_put_contents($file, '{}');

        $config = new JSONConfigurationManager($file, 'integration');

        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $bundle->setConfiguationHandler($config);

        $this->assertEquals($config, $bundle->getConfigurationHandler());
    }

    public function testCanSetEventManger(): void
    {
        $em = new Manager();

        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $bundle->setEventManager($em);

        $this->assertEquals($em, $bundle->getEventManager());
    }
}
