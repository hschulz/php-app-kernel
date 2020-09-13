<?php

declare(strict_types=1);

namespace Hschulz\Kernel;

use Hschulz\Event\AbstractEvent;

/**
 *
 */
class KernelEvent extends AbstractEvent implements KernelAware
{
    /**
     * The identifier string for the kernel pre-boot event.
     * @var string
     */
    const EVENT_PRE_BOOT = 'Hschulz.kernel.pre-boot';

    /**
     * The identifier string for the kernel post-boot event.
     * @var string
     */
    const EVENT_POST_BOOT = 'Hschulz.kernel.post-boot';

    /**
     * The identifier string for the kernel pre-shutdown event.
     * @var string
     */
    const EVENT_PRE_SHUTDOWN = 'Hschulz.kernel.pre-shutdown';

    /**
     * The identifier string for the kernel post-shutdown event.
     * @var string
     */
    const EVENT_POST_SHUTDOWN = 'Hschulz.kernel.post-shutdown';

    /**
     * The kernel object.
     * @var Kernel
     */
    protected $kernel = null;

    /**
     * Creates a new event object with the corresponding kernel object.
     *
     * @param Kernel $kernel The kernel object
     */
    public function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    /**
     * Returns the kernel instance.
     *
     * @return Kernel The kernel object
     */
    public function getKernel(): Kernel
    {
        return $this->kernel;
    }

    /**
     * Sets the kernel instance.
     *
     * @param Kernel $kernel The kernel object
     * @return void
     */
    public function setKernel(Kernel $kernel): void
    {
        $this->kernel = $kernel;
    }
}
