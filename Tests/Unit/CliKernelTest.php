<?php

namespace hschulz\Kernel\Tests\Unit;

use \hschulz\Kernel\Bundle\AbstractBundle;
use \hschulz\Kernel\CliKernel;
use \org\bovigo\vfs\vfsStream;
use \PHPUnit\Framework\TestCase;

final class CliKernelTest extends TestCase
{
    protected $config = null;

    protected $kernel = null;

    protected function setUp()
    {
        vfsStream::setup('integration');

        $file = vfsStream::url('integration/config.json');

        file_put_contents($file, '{}');

        $this->config = new \hschulz\Config\JSONConfigurationManager($file, 'integration');

        $this->config['Kernel']['timezone'] = 'Europe/Berlin';
        $this->config['Kernel']['display_errors'] = 'On';
        $this->config['Kernel']['error_reporting'] = E_ALL;
        $this->config['Kernel']['debug'] = true;

        $this->kernel = new CliKernel($this->config);
    }

    protected function tearDown()
    {
        $this->config = null;
    }

    public function testCanRegisterBundle()
    {
        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $this->kernel->registerBundle($bundle);

        $this->assertEquals(1, count($this->kernel->getBundles()));
        $this->assertEquals($bundle, $this->kernel->getBundles()[0]);
    }

    public function testCanRegisterBundles()
    {
        $this->kernel->registerBundles([
            $this->getMockForAbstractClass(AbstractBundle::class),
            $this->getMockForAbstractClass(AbstractBundle::class),
            $this->getMockForAbstractClass(AbstractBundle::class)
        ]);

        $this->assertEquals(3, count($this->kernel->getBundles()));
    }

    public function testCanNotRegisterAnyObjectAsBundle()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->kernel->registerBundles([new \stdClass()]);
    }

    public function testCanSetDebug()
    {
        $this->assertTrue($this->kernel->isDebug());

        $this->kernel->setDebug(false);

        $this->assertFalse($this->kernel->isDebug());
    }

    public function testHasConfig()
    {
        $this->assertEquals($this->config, $this->kernel->getConfigurationHandler());
    }

    public function testHasEventManager()
    {
        $this->assertInstanceOf(\hschulz\Event\EventManager::class, $this->kernel->getEventManager());
    }

    public function testCanSetEventManager()
    {
        $em = new \hschulz\Event\Manager();

        $this->kernel->setEventManager($em);

        $this->assertEquals($em, $this->kernel->getEventManager());
    }

    public function testCanBoot()
    {
        $this->assertTrue($this->kernel->boot());
    }

    public function testCanBootWithBundles()
    {
        $this->kernel->registerBundle($this->getMockForAbstractClass(AbstractBundle::class));
        $this->assertTrue($this->kernel->boot());
    }

    public function testCanShutdown()
    {
        $this->assertTrue($this->kernel->shutdown());
    }

    public function testCanShutdownWithBundles()
    {
        $this->kernel->registerBundle($this->getMockForAbstractClass(AbstractBundle::class));
        $this->assertTrue($this->kernel->shutdown());
    }

    public function testCanGetEnvironment()
    {
        $this->assertEquals('integration', $this->kernel->getEnvironment());
    }

    public function testCanGetStartTime()
    {
        $this->assertLessThan(microtime(true), $this->kernel->getStartTime());
    }
}
