<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCsvCommand extends Command
{
    protected static $defaultName = 'import:csv';

    protected function configure()
    {
        $this->setDescription('Imports data from a CSV file into the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Start export...');

        $io->progressFinish();
        $io->success('Export done ✅');
    }
}
