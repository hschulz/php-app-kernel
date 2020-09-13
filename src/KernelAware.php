<?php

declare(strict_types=1);

namespace Hschulz\Kernel;

/**
 *
 */
interface KernelAware
{

    /**
     * Returns the kernel instance.
     *
     * @return Kernel The kernel object
     */
    public function getKernel(): Kernel;

    /**
     * Sets the kernel instance.
     *
     * @param Kernel $kernel The kernel object
     * @return void
     */
    public function setKernel(Kernel $kernel): void;
}
