<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\ImdbMovies;
use App\Entity\YtCategories;
use App\Entity\YtChannels;
use App\Meltir\imdb;
use App\Meltir\meltir;
use App\Meltir\youtube;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// ...

class AdminController extends EasyAdminController
{

    protected function categoryToChannel(array $ids, $catName) {
        $category = $this->getDoctrine()->getRepository(YtCategories::class)->findOneBy(["name"=>$catName]);
        if (!$category) return;
        $channelRepo = $this->getDoctrine()->getRepository(YtChannels::class);
        $manager = $this->getDoctrine()->getManager();
        foreach ($ids as $id) {
            $channel = $channelRepo->find($id);
            $channel->setCategory($category);
            $manager->persist($channel);
        }

        $manager->flush();
    }

    /**
     * @param array $ids
     */
    public function unlistBatchAction(array $ids)
    {
        $this->categoryToChannel($ids,'unlisted');
    }

    /**
     * @param array $ids
     */
    public function newsBatchAction(array $ids) {
        $this->categoryToChannel($ids,'News');
    }


    /**
     * @param array $ids
     */
    public function funnyBatchAction(array $ids) {
        $this->categoryToChannel($ids,'Funny');
    }

    /**
     * @param array $ids
     */
    public function scienceBatchAction(array $ids) {
        $this->categoryToChannel($ids,'Science & Education');
    }

    /**
     * @param array $ids
     */
    public function diyBatchAction(array $ids) {
        $this->categoryToChannel($ids,'DIY');
    }

    /**
     * @param array $ids
     */
    public function geekBatchAction(array $ids) {
        $this->categoryToChannel($ids,'Geek & Tech');
    }

    /**
     * @param array $ids
     */
    public function retroBatchAction(array $ids) {
        $this->categoryToChannel($ids,'Retro');
    }

    /**
     * @param array $ids
     */
    public function devBatchAction(array $ids) {
        $this->categoryToChannel($ids,'Dev');
    }


    /**
     * @Route("/youtube_auth/", name="youtube_auth", methods={"GET"})
     * @param Request $request
     * @param youtube $youtube
     * @return Response
     */
    public function youtube_auth(Request $request, youtube $youtube) {
        $code = $request->query->get('code', 'fail');
        if ($code=='fail')
            return $this->render('admin_message.html.twig',['body_header'=>'Youtube channel import','body_message'=>'Youtube auth failure, nothing imported']);
        $youtube->setAuthCode($code);
        return $this->redirectToRoute($youtube->getReturnRoute());
    }

    /**
     *
     *
     * @Route("/youtube_import_channels/{pageToken}", name="youtube_channels_import", defaults={"pageToken":""})
     * @param youtube $youtube
     * @param string|null $pageToken
     * @param LoggerInterface $logger
     * @return RedirectResponse|Response
     */
    public function loadYoutubeChannels(youtube $youtube, $pageToken, LoggerInterface $logger) {
        $auth_url = $youtube->login('youtube_auth');
        if ($auth_url) {
            $logger->info('Google got auth url of '.print_r($auth_url,true));
            return $this->redirect($auth_url);
        }
        if ($pageToken = $youtube->updateChannelListFromYoutube($pageToken)) {
            return $this->redirectToRoute('youtube_channels_import',['pageToken'=>$pageToken]);
        }
        return $this->render('admin_message.html.twig',['body_header'=>'Youtube channel import','body_message'=>'Import successful !']);

    }

    /**
     * @Route("/youtube_import_videos/{page}", name="youtube_videos", defaults={"page":"1"})
     * @param youtube $youtube
     * @param int $page
     * @return RedirectResponse
     */
    public function loadYoutubeVideos(youtube $youtube, int $page) {
        $auth_url = $youtube->login('youtube_auth');
        if ($auth_url) {
            return $this->redirect($auth_url);
        }
        if ($youtube->addLatestVideos($page)) {
            return $this->redirectToRoute('youtube_videos',['page'=>$page+1]);
        }
        return $this->render('admin_message.html.twig',['body_header'=>'Youtube video import','body_message'=>'Imported your youtube videos(full page done) ']);
    }

    /**
     * @Route("/imdb_import_movies/{nextUrl}",name="imdb_import",defaults={"nextUrl":""},requirements={"nextUrl"=".+"})
     * @param Imdb $imdb
     * @param string $nextUrl
     * @return Response
     */
    public function imdbService(imdb $imdb, $nextUrl) {
        $nextUrl = base64_decode($nextUrl);
        $next = $imdb->processPage($nextUrl);
        if ($next) {
            return $this->redirectToRoute('imdb_import',['nextUrl'=>base64_encode($next)]);
        }
        return $this->render('admin_message.html.twig',['body_header'=>'IMDB ratings import','body_message'=>'Imported your imdb videos']);
    }

}