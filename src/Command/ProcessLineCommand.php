<?php

namespace App\Command;

use App\DataProvider\FileLineDataProvider;
use App\Filters\StartLimitFilter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'file to process')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filenametoProcess = $input->getArgument('file');

        if ($filenametoProcess) {
            $io->note(sprintf('File to process: %s', $filenametoProcess));
        }

        $start = 2;
        $limit = 7;
        
        $pipeline = take($this->fileLineDataProvider->provide($filenametoProcess));

        if (isset($start, $end)) {
            $pipeline->filter(new StartLimitFilter($start, $limit));
        }

        foreach ($pipeline as $line) {
            echo "$line\n";
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
