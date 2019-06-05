<?php

namespace App\Command;

use App\Meltir\imdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImdbRefreshCommand extends Command
{
    protected static $defaultName = 'app:imdb-refresh';

    /**
     * @var imdb
     */
    private $imdb;

    public function __construct(string $name = null, imdb $imdb)
    {
        $this->imdb = $imdb;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Import IMDB reviews')
            ->setHelp('Crawl over IMDB reviews for user and scrape movie ratings, titles, posters and other info')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Importing ratings from IMDB');
        $count = 1;
        $page = '';
        while ($page = $this->imdb->processPage($page)) {
            $output->writeln('Processed page '.$count);
            $count++;
        }
        $output->writeln('Processed page '.$count);

        $io->success('Finished processing IMDB ratings.');
    }
}
