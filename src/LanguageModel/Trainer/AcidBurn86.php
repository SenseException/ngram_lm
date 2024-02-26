<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\LanguageModel\Trainer;

use Override;
use Senseexception\NgramLm\ModelSource\ModelSource;

use function array_fill;
use function array_merge;
use function array_slice;
use function array_sum;
use function array_unique;
use function assert;
use function count;
use function file_get_contents;
use function implode;
use function is_array;
use function is_string;
use function mb_strtolower;
use function preg_split;
use function round;
use function str_word_count;

use const PREG_SPLIT_NO_EMPTY;

/**
 * This class is based on the original LanguageModel created by AcidBurn86.
 *
 * @phpstan-type language_model array<int, array<string, array<string, float|int>>>
 */
class AcidBurn86 implements Trainer
{
    /** @phpstan-var language_model */
    private array $models  = [];
    private int $vocabSize = 0;

    public function __construct(private readonly ModelSource $modelSource, private readonly int $ngrams)
    {
    }

    #[Override]
    public function train(string $filePath): void
    {
        $text = file_get_contents($filePath);

        assert(is_string($text));

        $text       = mb_strtolower($text);
        $paragraphs = preg_split('/\n+/', $text);

        assert(is_array($paragraphs));

        $this->vocabSize = count(array_unique(str_word_count($text, 1)));

        for ($n = $this->ngrams; $n > 0; $n--) {
            $this->models[$n] = [];

            foreach ($paragraphs as $paragraph) { //split into words
                $words = preg_split('/\s+|(?<=[!?])|(?=[!?])/u', $paragraph, -1, PREG_SPLIT_NO_EMPTY);

                assert(is_array($words));

                $start = array_fill(0, $n - 1, '<start>'); //add start and end tokens
                $end   = array_fill(0, $n - 1, '<end>');
                $words = array_merge($start, $words, $end);

                for ($i = 0; $i < count($words) - $n + 1; $i++) {
                    $slice = array_slice($words, $i, $n);
                    $key   = implode(' ', array_slice($slice, 0, $n - 1));
                    $word  = $slice[$n - 1];

                    if (! isset($this->models[$n][$key])) {
                        $this->models[$n][$key] = [];
                    }

                    if (! isset($this->models[$n][$key][$word])) {
                        $this->models[$n][$key][$word] = 0;
                    }

                    $this->models[$n][$key][$word]++;
                }
            }

            foreach ($this->models[$n] as $key => $nextWords) {
                $total = array_sum($nextWords);
                foreach ($nextWords as $word => $freq) {
                    $this->models[$n][$key][$word] = round(($freq + 1) / ($total + $this->vocabSize) * 100, 6);
                }
            }
        }

        $this->modelSource->save($this->models);
    }
}
