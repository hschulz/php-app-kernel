<?php

namespace hschulz\Kernel\Tests\Unit\Bundle;

use \hschulz\Config\JSONConfigurationManager;
use \hschulz\Kernel\Bundle\AbstractBundle;
use \org\bovigo\vfs\vfsStream;
use \PHPUnit\Framework\TestCase;
use function \file_put_contents;

final class AbstractBundleTest extends TestCase {

    public function testCanSetName() {

        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $bundle->setName('Integration');

        $this->assertEquals('Integration', $bundle->getName());
    }

    public function testCanSetConfig() {

        vfsStream::setup('integration');

        $file = vfsStream::url('integration/config.json');

        file_put_contents($file, '{}');

        $config = new JSONConfigurationManager($file, 'integration');

        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $bundle->setConfiguationHandler($config);

        $this->assertEquals($config, $bundle->getConfigurationHandler());
    }

    public function testCanSetEventManger() {

        $em = new \hschulz\Event\Manager();

        $bundle = $this->getMockForAbstractClass(AbstractBundle::class);

        $bundle->setEventManager($em);

        $this->assertEquals($em, $bundle->getEventManager());
    }
}
