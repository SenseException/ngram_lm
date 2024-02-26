<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\LanguageModel\Operator;

use Override;
use Senseexception\NgramLm\LanguageModel\Trainer\AcidBurn86 as Trainer;
use Senseexception\NgramLm\ModelSource\ModelSource;

use function array_slice;
use function array_sum;
use function count;
use function explode;
use function implode;
use function mt_getrandmax;
use function mt_rand;

/**
 * This class is based on the original LanguageModel created by AcidBurn86.
 *
 * @phpstan-import-type language_model from Trainer
 */
class AcidBurn86 implements Operator
{
    /** @phpstan-var language_model */
    private array $models = [];

    public function __construct(private readonly ModelSource $modelSource, private readonly int $ngrams)
    {
    }

    #[Override]
    public function loadModel(): void
    {
        $this->models = $this->modelSource->load();
    }

    #[Override]
    public function generateSentence(string $initialWord, int $length): string
    {
        $sentence = explode(' ', $initialWord);
        for ($i = count($sentence); $i < $length; $i++) {
            $n        = $this->ngrams;
            $nextWord = null;

            while ($nextWord === null && $n > 0) {
                $key      = implode(' ', array_slice($sentence, $i - $n + 1, $n - 1));
                $nextWord = $this->getNextWord($key, $n);
                $n--;
            }

            if ($nextWord === null) {
                break;
            }

            $sentence[] = $nextWord;//add next word to sentence
        }

        return implode(' ', $sentence);
    }

    private function getNextWord(string $key, int $n): string|null
    {
        if (! isset($this->models[$n][$key])) {
            return null;
        }

        $nextWords = $this->models[$n][$key];
        if (count($nextWords) === 1) { //avoid exact phrases
            return null;
        }

        $probSum = array_sum($nextWords);
        $rand    = mt_rand() / mt_getrandmax() * $probSum;
        $accum   = 0;
        foreach ($nextWords as $word => $prob) {
            $accum += $prob;
            if ($accum >= $rand) {
                // A number as an array key will be auto-casted as int. Therefore, a string cast is needed.
                return (string) $word;
            }
        }

        return null;
    }
}
