<?php

declare(strict_types=1);

namespace Hschulz\Kernel\Tests\Unit\Bundle;

use Hschulz\Kernel\Bundle\AbstractBundle;
use Hschulz\Kernel\Bundle\RegisterBundleEvent;
use PHPUnit\Framework\TestCase;

final class RegisterBundleEventTest extends TestCase
{
    public function testCanSetTarget(): void
    {
        $stub = $this->getMockForAbstractClass(AbstractBundle::class);

        $event = new RegisterBundleEvent($stub);
        $event->setBundle($stub);

        $this->assertEquals($stub, $event->getBundle());
    }
}
