<?php

namespace Hschulz\Kernel\Tests\Unit;

use Hschulz\Config\JSONConfigurationManager;
use Hschulz\Kernel\CliKernel;
use Hschulz\Kernel\KernelEvent;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class KernelEventTest extends TestCase
{
    public function testCanBeCreatedWithKernel(): void
    {
        vfsStream::setup('integration');

        $file = vfsStream::url('integration/config.json');

        file_put_contents($file, '{}');

        $config = new JSONConfigurationManager($file, 'integration');

        $config['Kernel']['timezone'] = 'Europe/Berlin';
        $config['Kernel']['display_errors'] = 'On';
        $config['Kernel']['error_reporting'] = E_ALL;
        $config['Kernel']['debug'] = true;

        $kernel = new CliKernel($config);

        $event = new KernelEvent($kernel);

        $this->assertEquals($kernel, $event->getKernel());
    }

    public function testCanKernelBeSet(): void
    {
        vfsStream::setup('integration');

        $file = vfsStream::url('integration/config.json');

        file_put_contents($file, '{}');

        $config = new JSONConfigurationManager($file, 'integration');

        $config['Kernel']['timezone'] = 'Europe/Berlin';
        $config['Kernel']['display_errors'] = 'On';
        $config['Kernel']['error_reporting'] = E_ALL;
        $config['Kernel']['debug'] = true;

        $kernel = new CliKernel($config);

        $event = new KernelEvent($kernel);

        $event->setKernel($kernel);

        $this->assertEquals($kernel, $event->getKernel());
    }
}
