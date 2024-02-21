<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\LanguageModel\Operator;

interface Operator
{
    public function generateSentence(string $initialWord, int $length): string;

    public function loadModel(): void;
}
