<?php

declare(strict_types=1);

namespace Hschulz\Kernel\Tests\Unit;

use Hschulz\Config\JSONConfigurationManager;
use Hschulz\Event\EventManager;
use Hschulz\Event\Manager;
use Hschulz\Kernel\Bundle\AbstractBundle;
use Hschulz\Kernel\CliKernel;
use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use stdClass;

final class CliKernelTest extends TestCase
{
    protected ?JSONConfigurationManager $config = null;

    protected ?CliKernel $kernel = null;

    protected function setUp(): void
    {
        vfsStream::setup('integration');

        $file = vfsStream::url('integration/config.json');

        file_put_contents($file, '{}');

        $this->config = new JSONConfigurationManager($file);

        $this->config['Kernel']['timezone'] = 'Europe/Berlin';
        $this->config['Kernel']['display_errors'] = 'On';
        $this->config['Kernel']['error_reporting'] = E_ALL;
        $this->config['Kernel']['debug'] = true;

        $this->kernel = new CliKernel($this->config);
    }

    protected function tearDown(): void
    {
        $this->config = null;
    }

    public function testCanRegisterBundle(): void
    {
        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $this->kernel->registerBundle($bundle);

        $this->assertEquals(1, count($this->kernel->getBundles()));
        $this->assertEquals($bundle, $this->kernel->getBundles()[0]);
    }

    public function testCanRegisterBundles(): void
    {
        $this->kernel->registerBundles([
            $this->getMockForAbstractClass(AbstractBundle::class),
            $this->getMockForAbstractClass(AbstractBundle::class),
            $this->getMockForAbstractClass(AbstractBundle::class)
        ]);

        $this->assertEquals(3, count($this->kernel->getBundles()));
    }

    public function testCanNotRegisterAnyObjectAsBundle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->kernel->registerBundles([new stdClass()]);
    }

    public function testCanSetDebug(): void
    {
        $this->assertTrue($this->kernel->isDebug());

        $this->kernel->setDebug(false);

        $this->assertFalse($this->kernel->isDebug());
    }

    public function testHasConfig(): void
    {
        $this->assertEquals($this->config, $this->kernel->getConfigurationHandler());
    }

    public function testHasEventManager(): void
    {
        $this->assertInstanceOf(EventManager::class, $this->kernel->getEventManager());
    }

    public function testCanSetEventManager(): void
    {
        $em = new Manager();

        $this->kernel->setEventManager($em);

        $this->assertEquals($em, $this->kernel->getEventManager());
    }

    public function testCanBoot(): void
    {
        $this->assertTrue($this->kernel->boot());
    }

    public function testCanBootWithBundles(): void
    {
        $this->kernel->registerBundle($this->getMockForAbstractClass(AbstractBundle::class));
        $this->assertTrue($this->kernel->boot());
    }

    public function testCanShutdown(): void
    {
        $this->assertTrue($this->kernel->shutdown());
    }

    public function testCanShutdownWithBundles(): void
    {
        $this->kernel->registerBundle($this->getMockForAbstractClass(AbstractBundle::class));
        $this->assertTrue($this->kernel->shutdown());
    }

    public function testCanGetStartTime(): void
    {
        $this->assertLessThan(microtime(true), $this->kernel->getStartTime());
    }
}
