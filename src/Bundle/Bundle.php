<?php

declare(strict_types=1);

namespace Hschulz\Kernel\Bundle;

use Hschulz\Config\Configurable;
use Hschulz\Event\EventAware;

/**
 *
 */
interface Bundle extends EventAware, Configurable
{
    /**
     * Implementing classes should put all logic in this method that is required
     * to have a fully functional bundle instance.
     *
     * @return bool
     */
    public function boot(): bool;

    /**
     * Implementing classes should put all logic in this mehtod that is required
     * to disable all functionality of this bundle.
     *
     * @return bool
     */
    public function shutdown(): bool;

    /**
     * Returns the name of this bundle.
     *
     * @return string The bundle name
     */
    public function getName(): string;

    /**
     * Sets the name for this bundle.
     *
     * @param string $name The bundle name
     * @return void
     */
    public function setName(string $name): void;
}
