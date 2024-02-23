<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\Command;

use DateTimeImmutable;
use Senseexception\NgramLm\LanguageModel\Operator\Operator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function assert;
use function is_numeric;
use function is_string;
use function sprintf;

class SentenceGenerator extends Command
{
    public function __construct(private readonly Operator $operator)
    {
        parent::__construct('generate');
    }

    protected function configure(): void
    {
        $this->addArgument('modelfile', InputArgument::REQUIRED, 'The created language model file');
        $this->addArgument('word', InputArgument::REQUIRED, 'The word used in the language model');
        $this->addOption('length', 'l', InputOption::VALUE_OPTIONAL, 'The amount of words used for the generated output', '50');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $textfile = $input->getArgument('modelfile');
        $word     = $input->getArgument('word');
        $length   = $input->getOption('length');

        assert(is_string($textfile) && is_string($word) && is_numeric($length));

        $startDate = new DateTimeImmutable();
        $output->writeln('Loading model...', OutputInterface::VERBOSITY_VERBOSE);

        $this->operator->loadModel();

        $endDate  = new DateTimeImmutable();
        $interval = $startDate->diff($endDate);
        $output->writeln(sprintf(
            'The language model was loaded in %d hours, %d minutes and %d seconds',
            $interval->h,
            $interval->i,
            $interval->s,
        ), OutputInterface::VERBOSITY_VERBOSE);

        $generatedSentence = $this->operator->generateSentence($word, (int) $length);
        $output->writeln(sprintf('<info>%s</info>', $generatedSentence));

        return 0;
    }
}
