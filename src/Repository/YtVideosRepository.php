<?php

namespace App\Repository;

use App\Entity\YtChannels;
use App\Entity\YtVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Google_Client;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Google_Service_YouTube;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @method YtVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method YtVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method YtVideos[]    findAll()
 * @method YtVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YtVideosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
        parent::__construct($registry, YtVideos::class);
    }

    /**
     * @var Stopwatch
     */
    protected $stopwatch;


    public function addLatestVideosForChannel(Google_Client $client,YtChannels $channel) {
        $service = new Google_Service_YouTube($client);

        $this->stopwatch->start('Google');
        $playlist = $service->playlistItems->listPlaylistItems('snippet',['playlistId'=>$channel->getUploadPlaylist(),'maxResults'=>30]);
        $this->stopwatch->stop('Google');
        $manager = $this->getEntityManager();
        $count = 0;
        foreach ($playlist->getItems() as $item) {
            if (!$this->findBy(['videoid'=>$item->snippet->resourceId->videoId])) {
                $count++;
                $video = new YtVideos();
                $img_url = $item->snippet->thumbnails->high->url;
                if ($item->snippet->thumbnails->standard) $img_url = $item->snippet->thumbnails->standard->url;
                $video->setThumb($img_url);
                $video->setChannel($channel);
                $video->setTitle($item->snippet->title);
                $video->setVideoid($item->snippet->resourceId->videoId);
                $video->setActive(true);
                $video->setDatePublished(new \DateTime($item->snippet->publishedAt));
                $video->setLiked(false);
                $manager->persist($video);
            }
        }
        $manager->flush();
        return $count;
    }


    public function getLatestVideos(YtChannels $channel, $count = 10, $page = 1) {
        $query = $this->createQueryBuilder('n')
            ->andWhere('n.channel = :chan')
            ->setParameter('chan',$channel)
            ->orderBy('n.date_published','DESC')
            ->setMaxResults($count);
        if ($page>1) $query->setFirstResult($page*$count);
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return YtVideos[] Returns an array of YtVideos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?YtVideos
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
