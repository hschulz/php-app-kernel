<?php

declare(strict_types=1);

namespace Hschulz\Kernel;

use function count;
use function date_default_timezone_set;
use function error_reporting;
use Hschulz\Config\Configurable;
use Hschulz\Config\ConfigurationManager;
use Hschulz\Event\EventManager;
use Hschulz\Event\Manager;
use Hschulz\Kernel\Bundle\Bundle;
use Hschulz\Kernel\Bundle\RegisterBundleEvent;
use function ini_set;
use InvalidArgumentException;
use function microtime;

/**
 * Description of Kernel
 */
abstract class AbstractKernel implements Configurable, Kernel
{
    /**
     * Identifier for a development environment.
     * @var string
     */
    public const ENV_DEVELOPMENT = 'development';

    /**
     * Identifier for a production environment.
     * @var string
     */
    public const ENV_PRODUCTION = 'production';

    /**
     * The currently selected environment.
     *
     * @var string
     */
    protected string $environment = '';

    /**
     * Specifies if the kernel is currently running in debug mode.
     *
     * @var bool
     */
    protected bool $isDebug = false;

    /**
     * Starting time of the kernel initialization.
     *
     * @var float
     */
    protected float $startTime = 0.0;

    /**
     * Shows if the kernel has been booted or not.
     *
     * @var string
     */
    protected bool $isBooted = false;

    /**
     * All registered bundles.
     *
     * @var array
     */
    protected array $bundles = [];

    /**
     * The event manger instance.
     *
     * @var EventManager|null
     */
    protected ?EventManager $eventManager = null;

    /**
     * The configuration manager instance.
     *
     * @var ConfigurationManager|null
     */
    protected ?ConfigurationManager $config = null;

    /**
     * Creates a new kernel instance.
     *
     * @param ConfigurationManager $config The configuration manager
     */
    public function __construct(ConfigurationManager $config)
    {
        $this->environment  = $config->getEnvironment();
        $this->isDebug      = false;
        $this->startTime    = microtime(true);
        $this->isBooted     = false;
        $this->bundles      = [];
        $this->eventManager = new Manager();

        $this->setConfiguationHandler($config);
    }

    /**
     * Returns the configuration manager object for this instance.
     *
     * @return ConfigurationManager The configuration manager
     */
    public function getConfigurationHandler(): ConfigurationManager
    {
        return $this->config;
    }

    /**
     * Sets the configuration manager instance for this object.
     *
     * @param ConfigurationManager $manager The configuration manager
     */
    public function setConfiguationHandler(ConfigurationManager $manager): void
    {

        /* Assign the configuration manager */
        $this->config = $manager;

        /* If possible assign the timezone from the config */
        if (isset($this->config['Kernel']['timezone'])) {
            date_default_timezone_set($this->config['Kernel']['timezone']);
        }

        /* If possible assign the display_errors value from the config */
        if (isset($this->config['Kernel']['display_errors'])) {
            ini_set('display_errors', (string) $this->config['Kernel']['display_errors']);
        }

        /* If possible assign the error reporting value from the config */
        if (isset($this->config['Kernel']['error_reporting'])) {
            ini_set('error_reporting', (string) $this->config['Kernel']['error_reporting']);
            error_reporting($this->config['Kernel']['error_reporting']);
        }

        /* Set debug value from config */
        $this->isDebug = $this->config['Kernel']['debug'] ?? false;
    }

    /**
     * Returns the current debug mode.
     *
     * @return bool True if the debug mode is enabled
     */
    public function isDebug(): bool
    {
        return $this->isDebug;
    }

    /**
     * Sets the debug mode for the kernel.
     *
     * @param bool $isDebug True enables the debug mode
     */
    public function setDebug(bool $isDebug): void
    {
        $this->isDebug = $isDebug;
    }

    /**
     *
     * @return bool
     */
    public function boot(): bool
    {
        if (!$this->isBooted) {
            $preEvent = new KernelEvent($this);
            $preEvent->setName(KernelEvent::EVENT_PRE_BOOT);

            $this->eventManager->trigger($preEvent);

            $this->initializeBundles();

            $this->isBooted = true;

            $postEvent = new KernelEvent($this);
            $postEvent->setName(KernelEvent::EVENT_POST_BOOT);

            $this->eventManager->trigger($postEvent);
        }

        return $this->isBooted;
    }

    /**
     *
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     *
     * @return float
     */
    public function getStartTime(): float
    {
        return $this->startTime;
    }

    /**
     *
     * @return bool
     */
    public function shutdown(): bool
    {
        $preEvent = new KernelEvent($this);
        $preEvent->setName(KernelEvent::EVENT_PRE_SHUTDOWN);

        $this->eventManager->trigger($preEvent);

        $isShutdown = false;

        for ($i = 0, $c = count($this->bundles); $i < $c; $i++) {
            $isShutdown = $isShutdown || $this->bundles[$i]->shutdown();
        }

        $this->isBooted = false || $isShutdown;

        $postEvent = new KernelEvent($this);
        $postEvent->setName(KernelEvent::EVENT_POST_SHUTDOWN);

        $this->eventManager->trigger($postEvent);

        return !$this->isBooted;
    }

    /**
     * Returns an array of Bundle objects that are assigned.
     *
     * @return array The array of bundles
     */
    public function getBundles(): array
    {
        return $this->bundles;
    }

    /**
     *
     * @param Bundle $bundle
     * @return void
     */
    public function registerBundle(Bundle $bundle): void
    {
        $preEvent = new RegisterBundleEvent($bundle);
        $preEvent->setName(RegisterBundleEvent::EVENT_PRE_REGISTER);

        $this->eventManager->trigger($preEvent);

        $this->bundles[] = $bundle;

        $postEvent = new RegisterBundleEvent($bundle);
        $postEvent->setName(RegisterBundleEvent::EVENT_POST_REGISTER);

        $this->eventManager->trigger($postEvent);
    }

    /**
     *
     * @param array $bundles
     * @throws InvalidArgumentException
     * @return void
     */
    public function registerBundles(array $bundles): void
    {
        foreach ($bundles as $bundle) {
            if ($bundle instanceof Bundle) {
                $this->registerBundle($bundle);
            } else {
                throw new InvalidArgumentException();
            }
        }
    }

    /**
     *
     * @return void
     */
    protected function initializeBundles(): void
    {
        for ($i = 0, $c = count($this->bundles); $i < $c; $i++) {
            $this->bundles[$i]->setEventManager($this->eventManager);
            $this->bundles[$i]->setConfiguationHandler($this->config);
            $this->bundles[$i]->boot();
        }
    }

    /**
     * Returns the event manager object.
     *
     * @return EventManager The event manager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * Sets the event manager object for this instance.
     *
     * @param EventManager $manager The event manager object
     * @return void
     */
    public function setEventManager(EventManager $manager): void
    {
        $this->eventManager = $manager;
    }
}
