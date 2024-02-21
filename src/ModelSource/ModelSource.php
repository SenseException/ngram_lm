<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\ModelSource;

use Senseexception\NgramLm\LanguageModel\Trainer\AcidBurn86 as Trainer;

/** @phpstan-import-type language_model from Trainer */
interface ModelSource
{
    /** @phpstan-return language_model */
    public function load(): array;

    /** @phpstan-param language_model $models */
    public function save(array $models): void;
}
