<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\ModelSource;

use Override;
use Senseexception\NgramLm\LanguageModel\Trainer\AcidBurn86 as Trainer;

use function assert;
use function file_get_contents;
use function file_put_contents;
use function is_string;
use function serialize;
use function unserialize;

/** @phpstan-import-type language_model from Trainer */
class File implements ModelSource
{
    public function __construct(private string $filePath)
    {
    }

    /** @return language_model */
    #[Override]
    public function load(): array
    {
        $serialiedModels = file_get_contents($this->filePath);

        assert(is_string($serialiedModels));

        /** @phpstan-var language_model $models */
        $models = unserialize($serialiedModels);

        return $models;
    }

    /** @param language_model $models */
    #[Override]
    public function save(array $models): void
    {
        file_put_contents($this->filePath, serialize($models));
    }
}
