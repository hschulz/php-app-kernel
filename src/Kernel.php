<?php

namespace hschulz\kernel;

use \hschulz\Event\EventAware;
use \hschulz\Kernel\Bundle\Bundle;

/**
 *
 */
interface Kernel extends EventAware
{

    /**
     * Implementing classes should put all logic here that is required
     * to fully enable the kernel instance.
     *
     * @return bool True if the kernel was started without errors
     */
    public function boot(): bool;

    /**
     * Implementing classes should put all logic here that is required
     * to disable all functions of the kernel instance.
     *
     * @return bool
     */
    public function shutdown(): bool;

    /**
     *
     * @return float
     */
    public function getStartTime(): float;

    /**
     *
     * @return array
     */
    public function getBundles(): array;

    /**
     *
     * @param Bundle $bundle
     * @return void
     */
    public function registerBundle(Bundle $bundle): void;

    /**
     *
     * @param array $bundles
     * @return void
     */
    public function registerBundles(array $bundles): void;
}
