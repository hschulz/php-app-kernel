<?php

namespace hschulz\Kernel\Tests\Unit\Bundle;

use \hschulz\Kernel\Bundle\AbstractBundle;
use \hschulz\Kernel\Bundle\RegisterBundleEvent;
use \PHPUnit\Framework\TestCase;

final class RegisterBundleEventTest extends TestCase
{
    public function testCanSetTarget()
    {
        $stub = $this->getMockForAbstractClass(AbstractBundle::class);

        $event = new RegisterBundleEvent($stub);
        $event->setBundle($stub);

        $this->assertEquals($stub, $event->getBundle());
    }
}
