<?php

declare(strict_types=1);

namespace Hschulz\Kernel\Bundle;

use Hschulz\Event\AbstractEvent;

/**
 *
 */
class RegisterBundleEvent extends AbstractEvent
{
    /**
     * Identifier for the bundle pre-register event.
     * @var string
     */
    public const EVENT_PRE_REGISTER = 'Hschulz.kernel.bundle.pre-register';

    /**
     * Identifier for the bundle post-register event.
     * @var string
     */
    public const EVENT_POST_REGISTER = 'Hschulz.kernel.bundle.post-register';

    /**
     * The bundle object that is the cause of the event.
     * @var Bundle|null
     */
    protected ?Bundle $bundle = null;

    /**
     * Creates a new event object with the corresponding bundle instance.
     *
     * @param Bundle $bundle The bundle object
     */
    public function __construct(Bundle $bundle)
    {
        parent::__construct();
        $this->bundle = $bundle;
    }

    /**
     * Assigns the bundle instance to the event.
     *
     * @param Bundle $bundle The bundle instance
     * @return void
     */
    public function setBundle(Bundle $bundle): void
    {
        $this->bundle = $bundle;
    }

    /**
     * Returns the bundle instance set for this event.
     *
     * @return Bundle The bundle instance
     */
    public function getBundle(): Bundle
    {
        return $this->bundle;
    }
}
