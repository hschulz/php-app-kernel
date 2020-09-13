<?php

declare(strict_types=1);

namespace Hschulz\Kernel\Bundle;

use Hschulz\Config\ConfigurationManager;
use Hschulz\Event\EventManager;

/**
 *
 */
abstract class AbstractBundle implements Bundle
{
    /**
     * The configuration manager.
     *
     * @var ConfigurationManager|null
     */
    protected ?ConfigurationManager $config = null;

    /**
     * The event manager.
     * @var EventManager|null
     */
    protected ?EventManager $eventManager = null;

    /**
     * The bundle name.
     * @var string
     */
    protected string $name = '';

    /**
     * Creates a new bundle without an event manager or config.
     *
     * @param string $name An optional name for this bundle
     */
    public function __construct(string $name = '')
    {
        $this->eventManager = null;
        $this->config       = null;
        $this->name         = $name;
    }

    /**
     * Assigns the given event manager to the instance.
     *
     * @param EventManager $eventManager The event manager object
     * @return void
     */
    public function setEventManager(EventManager $eventManager): void
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Returns the event manager instance that was assigned to this instance.
     *
     * @return EventManager|null The event manager object
     */
    public function getEventManager(): ?EventManager
    {
        return $this->eventManager;
    }

    /**
     * Assigns the given configuration manager to this instance.
     *
     * @param ConfigurationManager $config The configuration manager object
     * @return void
     */
    public function setConfiguationHandler(ConfigurationManager $config): void
    {
        $this->config = $config;
    }

    /**
     * Returns the configuration manager object that was assigned to this instance.
     *
     * @return ConfigurationManager|null The configuration manager object
     */
    public function getConfigurationHandler(): ?ConfigurationManager
    {
        return $this->config;
    }

    /**
     * Returns the name of this bundle.
     *
     * @return string The bundle name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name for this bundle.
     *
     * @param string $name The bundle name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Implementing classes should put all logic in this method that is required
     * to have a fully functional bundle instance.
     *
     * @return bool
     */
    abstract public function boot(): bool;

    /**
     * Implementing classes should put all logic in this mehtod that is required
     * to disable all functionality of this bundle.
     *
     * @return bool
     */
    abstract public function shutdown(): bool;
}
