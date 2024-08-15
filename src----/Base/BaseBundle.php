<?php

namespace Untek\Core\Bundle\Base;


use Untek\Core\Bundle\Interfaces\BundleDepsInterface;

/**
 * Абстрактный класс бандла.
 *
 * @method string getName() Имя бандла
 *
 * @method array container() конфигурация контейнера (DI)
 * @method array entityManager() конфигурация менеджера сущностей
 * @method array eventDispatcher() конфигурация диспетчера событий
 * @method array i18next() переводы в формате I18Next
 * @method array rbac() конфигурация ролей, полномочий, наследования RBAC
 * @method array migration() миграции БД
// * @method array console() команды консоли
 * @method array symfonyRpc() RPC-роуты
 * @method array symfonyAdmin() роуты админки
 * @method array symfonyWeb() роуты пользовательской части
 * @method array telegramRoutes() роуты для Telegram-бота
 */
abstract class BaseBundle implements BundleDepsInterface
{

    private $loaders;

    /**
     * Зависимости (бандлы)
     * @return array
     */
    public function deps(): array
    {
        return [];
    }

    /**
     * Что импортировать из бандлов:
     *
     * container - конфигурация контейнера (DI)
     * entityManager - конфигурация менеджера сущностей
     * i18next - переводы в формате I18Next
     * rbac - конфигурация ролей, полномочий, наследования RBAC
     * migration - миграции БД
     * console - команды консоли
     * symfonyRpc - RPC-роуты
     * symfonyAdmin - роуты админки
     * symfonyWeb - роуты пользовательской части
     * telegramRoutes - роуты для Telegram-бота
     *
     * Имена загрузчиков совпадают с названиями методов с конфигурацией в калссе бандла.
     *
     * @return array
     */
    public function getLoaders(): array
    {
        return $this->loaders;
    }

    /**
     *
     * @param array $loaders Массив имен загрузчиков
     */
    public function __construct(array $loaders = [])
    {
        $this->loaders = $loaders;
    }

//    /**
//     * команды консоли
//     * @return array
//     */
//    public function console(): array
//    {
//        return [];
//    }

//    /**
//     * конфигурация контейнера (DI)
//     * @return array
//     */
//    public function container(): array
//    {
//        return [];
//    }

//    /**
//     * конфигурация менеджера сущностей
//     * @return array
//     */
//    public function entityManager(): array
//    {
//        return [];
//    }

//    /**
//     * конфигурация ролей, полномочий, наследования RBAC
//     * @return array
//     */
//    public function rbac(): array
//    {
//        return [];
//    }
}
