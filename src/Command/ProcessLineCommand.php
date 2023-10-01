<?php

namespace App\Command;

use App\DataProvider\FileLineDataProvider;
use App\Filters\RegexFilter;
use App\Filters\StartLimitFilter;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function Pipeline\take;

#[AsCommand(
    name: 'app:process:line',
    description: 'Process lines of a file',
)]
class ProcessLineCommand extends Command
{
    public function __construct(
        private readonly FileLineDataProvider $fileLineDataProvider
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'file to process')
            ->addOption('start', 's', InputOption::VALUE_REQUIRED, 'First line to process')
            ->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Last line to process')
            ->addOption('regex', 'r', InputOption::VALUE_REQUIRED, 'Regex filter')
            ->addOption('count', 'c', InputOption::VALUE_NONE, 'Count line');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filenameToProcess = $input->getArgument('file');

        if ($filenameToProcess) {
            $io->note(sprintf('File to process: %s', $filenameToProcess));
        }

        $start = $input->getOption('start');
        $limit = $input->getOption('limit');
        $regex = $input->getOption('regex');
        $count = $input->getOption('count');

        if ($io->isVerbose()) {
            $io->comment("start: $start");
            $io->comment("limit: $limit");
            $io->comment("regex: $regex");
            $io->comment("count: ". intval($count));
        }

        $pipeline = take($this->fileLineDataProvider->provide($filenameToProcess));
        if ($start || $limit) {
            $pipeline->filter(new StartLimitFilter($start, $limit));
        }

        if (!empty($regex)) {
            $pipeline->filter(new RegexFilter($regex));
        }

        if ($count) {
            echo "{$pipeline->count()}\n";
        } else {
            foreach ($pipeline as $line) {
                echo "$line\n";
            }
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
