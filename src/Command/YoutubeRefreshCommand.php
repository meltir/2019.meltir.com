<?php

namespace App\Command;

use App\Meltir\youtube;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class YoutubeRefreshCommand extends Command
{
    protected static $defaultName = 'app:youtube-refresh';

    /**
     * @var youtube
     */
    private $youtube;

    public function __construct(string $name = null, youtube $youtube)
    {
        $this->youtube = $youtube;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Update youtube channels and video feeds')
            ->setHelp(<<<'EOF'
This command fetches all of the youtube channels subscribed by the user.
Then it fetches the last 30 videos from active channels.
Optimistacly assumes no more than 30 videos are published per day.
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $youtube = $this->youtube;
        $io->title('Importing youtube content');
        $io->section('Logging in...');
        $youtube->login('youtube_channels_import');
        $output->writeln("Logged into youtube");
        $io->section('Importing channels');
        $next_page = $youtube->updateChannelListFromYoutube();
        $output->writeln('Fetched the first channels page');
        while ($next_page) {
            $output->writeln('Fetching channels page '.$next_page);
            $next_page = $youtube->updateChannelListFromYoutube($next_page);
        }
        $io->section('Importing videos for active channels');
        $page = 1 ;
        while ($end = $youtube->addLatestVideos($page)) {
            $output->writeln('Fetched videos page '.$page);
            $page++;
        }
        $output->writeln('Fetched videos page '.$page);
        $io->success("Import finished !\n");
    }
}
