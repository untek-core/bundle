<?php

namespace Untek\Core\Bundle\Interfaces;

/**
 * Зависимости бандла.
 */
interface BundleDepsInterface
{

    /**
     * Получить зависимости.
     *
     * @return array Массив бандлов
     */
    public function deps(): array;
}
