<?php

declare(strict_types=1);

namespace Senseexception\NgramLm\Command;

use DateTimeImmutable;
use Senseexception\NgramLm\LanguageModel\Trainer\Trainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function assert;
use function is_string;
use function sprintf;

class CreateModel extends Command
{
    public function __construct(private readonly Trainer $trainer)
    {
        parent::__construct('create');
    }

    protected function configure(): void
    {
        $this->addArgument('sourcefile', InputArgument::REQUIRED, 'The textfile that is used to create the language model');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $textfile = $input->getArgument('sourcefile');

        assert(is_string($textfile));

        $startDate = new DateTimeImmutable();
        $output->writeln(sprintf('Started training at: %s', $startDate->format('H:i:s')), OutputInterface::VERBOSITY_VERBOSE);

        $this->trainer->train($textfile);

        $endDate = new DateTimeImmutable();
        $output->writeln(sprintf('Finished training at: %s', $endDate->format('H:i:s')), OutputInterface::VERBOSITY_VERBOSE);

        $interval = $startDate->diff($endDate);
        $output->writeln(sprintf(
            'The language model was created in %d hours, %d minutes and %d seconds',
            $interval->h,
            $interval->i,
            $interval->s,
        ), OutputInterface::VERBOSITY_VERBOSE);

        return 0;
    }
}
