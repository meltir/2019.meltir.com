<?php
namespace App\Meltir;
use App\Entity\ImdbMovies;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

class imdb {

    /**
     * @var Crawler
     */
    private $page = null;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * imdb constructor.
     * @param EntityManagerInterface $manager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $manager, LoggerInterface $logger) {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * Fetches page from url and populates $page with the crawler for the response
     * @param $url
     */
    private function getUrl($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($curl);
        $this->page = new Crawler($response,'https://www.imdb.com');
    }

    /**
     * Fetch the first page of the reviews
     */
    private function fetchInitialPage() {
        $this->getUrl('https://www.imdb.com/user/ur26174471/ratings');
    }

    /**
     * Find the next page url
     * @return string
     */
    private function getNextPage() {
        if (!$this->page) $this->fetchInitialPage();
        $selector = '#ratings-container > div.footer.filmosearch > div > div > a.flat-button.lister-page-next.next-page';
        try {
            $next_page_url = $this->page->filter($selector)->link()->getUri();
            return $next_page_url;
        } catch (\InvalidArgumentException $e) {
            return '';
        }
    }


    /**
     * This goes to hell if imdb ever change their page layout.
     * Fortunately they have not done so in a few years.
     * Takes in a crawler with an individual movie blok, processes it, puts it into the db
     *
     * @param Crawler $item
     * @throws \Exception
     */
    private function addMovie(Crawler $item) {
        $imdb_repo = $this->manager->getRepository(ImdbMovies::class);
        $title = $item->filter('h3 > a')->text();
        $old = $imdb_repo->findOneBy(['title'=>$title]);
        if ($old) {
            $movie = $old;
        } else {
            $movie = new ImdbMovies();
        }

        $movie->setTitle($title);
        $movie->setImdbLink($item->filter('h3 > a')->link()->getUri());

        // imdb displays posters as dynamicly resized and cropped images. need to tweak them a little to fit my site
        $dynamic_image = $item->filter('img')->attr('loadlate');
        $url1 = preg_replace('/UY209_CR[0-9]+,0,140,209/','UY896_CR0,0,600,896',$dynamic_image);
        $url2 = preg_replace('/UX140_CR[0-9]+,0,140,209/','UX600_CR0,0,600,896',$dynamic_image);
        if ($dynamic_image != $url2) {
            $movie->setPoster($url2);
        } else if ($dynamic_image != $url1) {
            $movie->setPoster($url1);
        } else $movie->setPoster($dynamic_image);
        //#ratings-container > div:nth-child(3) > div.lister-item-content > h3 > span.lister-item-year.text-muted.unbold
        $year = $item->filter('h3 > span.lister-item-year.text-muted.unbold')->text();
        preg_match('/[0-9]{4}/',$year,$year_arr);
//        $this->logger->info('Title '.$movie->getTitle().' year found is '.$year);
        $movie->setYear(new \DateTime($year_arr[0].'-01-01'));
        $movie->setMyRating($item->filter('div.ipl-rating-star.ipl-rating-star--other-user.small > span.ipl-rating-star__rating')->text());
        $movie->setSynopsis($item->filter('p:nth-child(6)')->text());
        $this->manager->persist($movie);
        $this->manager->flush();
    }


    /**
     * Process a page from a url (or first page if none provided)
     * @param string $page
     * @return string url of the next page of reviews
     * @throws \Exception
     */
    public function processPage($page = '') {
        if (!$page) $this->fetchInitialPage();
        else $this->getUrl($page);
        $item = $this->page->filter('#ratings-container > div');
        $finished = false;
        while (!$finished) {
            try {
                $this->addMovie($item);
                $item = $item->nextAll();
            } catch (\InvalidArgumentException $e) {
                $finished = true;
            }
        }
        return $this->getNextPage();
    }

}