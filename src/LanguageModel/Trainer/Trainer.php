<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\LanguageModel\Trainer;

interface Trainer
{
    public function train(string $filePath): void;
}
