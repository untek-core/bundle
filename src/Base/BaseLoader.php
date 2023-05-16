<?php

namespace Untek\Core\Bundle\Base;

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Untek\Core\ConfigManager\Interfaces\ConfigManagerInterface;
use Untek\Core\Container\Traits\ContainerAttributeTrait;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;
use Untek\Lib\Components\Time\Enums\TimeEnum;

/**
 * Абстрактный класс импорта конкретной конфигурации бандла.
 */
abstract class BaseLoader
{

    use ContainerAttributeTrait;

    /**
     * @var string Имя загрузчика.
     *
     * Обычно имя загрузчика и имя метода в бандле совпадают.
     */
    protected $name;

    /**
     * @var ConfigManagerInterface Реестр для хранения конфигов
     */
    private $configManager;

    /**
     * Загрузить конфигурации из списка бандлов.
     *
     * @param array $bundles
     */
    abstract public function loadAll(array $bundles): void;

    public function __construct(ContainerInterface $container, ConfigManagerInterface $configManager)
    {
        $this->setContainer($container);
        $this->setConfigManager($configManager);
    }

    protected function getCacheKey(): string {
        return static::class;
    }

    public function getValueFromCache() {
        /** @var ItemInterface $item */
        $item = $this->getCache()->getItem($this->getCacheKey());
        return $item->get();
    }

    public function setValueToCache($value) {
        /** @var ItemInterface $item */
        $item = $this->getCache()->getItem($this->getCacheKey());
        $item->set($value);
        $this->getCache()->save($item);
    }

    public function getCache(): AdapterInterface
    {
        $cacheDirectory = getenv('CACHE_DIRECTORY');
        $adapter = new FilesystemAdapter('bootstrapApp', TimeEnum::SECOND_PER_DAY, $cacheDirectory);
//        $adapter->setLogger($container->get(LoggerInterface::class));
        return $adapter;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    protected function getConfigManager(): ConfigManagerInterface
    {
        return $this->configManager;
    }

    protected function setConfigManager(ConfigManagerInterface $configManager): void
    {
        $this->configManager = $configManager;
    }

    /**
     * Загрузить конфигурации из одного бандла.
     *
     * @param BaseBundle $bundle
     * @return array
     */
    protected function load(BaseBundle $bundle): array
    {
        if (!$this->isAllow($bundle)) {
            return [];
        }
        $arguments = [];
        $handler = [$bundle, $this->getName()];
        return call_user_func_array($handler, $arguments);
    }

    /**
     * Вызов метода конфигурации.
     * 
     * Агрументы метода резолвятся из DI-конейнера.
     * 
     * @param BaseBundle $bundle
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function callMethod(BaseBundle $bundle): mixed
    {
        if (!$this->isAllow($bundle)) {
            return [];
        }

        $instanceResolver = new InstanceResolver($this->container);
        return $instanceResolver->callMethod2($bundle, $this->name);
        
//        $callback = [$bundle, $this->name];
//        $argumentResolver = $this->container->get(ArgumentMetadataResolver::class);
//        return $argumentResolver->call($callback);
    }

    /**
     * Проверяет, доступна ли конфигурация у бандла.
     *
     * @param BaseBundle $bundle
     * @return bool
     */
    protected function isAllow(BaseBundle $bundle): bool
    {
        return method_exists($bundle, $this->getName());
    }
}
