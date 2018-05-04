<?php

namespace hschulz\Kernel\Tests\Unit\Bundle;

use \PHPUnit\Framework\TestCase;
use \hschulz\Kernel\Bundle\RegisterBundleEvent;
use \hschulz\Kernel\Bundle\AbstractBundle;

final class RegisterBundleEventTest extends TestCase {

    public function testCanSetTarget() {

        $stub = $this->getMockForAbstractClass(AbstractBundle::class);

        $event = new RegisterBundleEvent($stub);
        $event->setBundle($stub);

        $this->assertEquals($stub, $event->getBundle());
    }
}
