<?php
/**
 * Created by PhpStorm.
 * User: meltir
 * Date: 3/19/2019
 * Time: 8:15 PM
 */

namespace App\Controller;

use App\Entity\ImdbMovies;
use App\Entity\YtCategories;
use App\Entity\YtChannels;
use App\Entity\YtVideos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PagePost;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="home", methods="GET")
     * @return Response
     */
    public function homepage() {
        $repository = $this->getDoctrine()
            ->getRepository(PagePost::class);
        $posts = $repository->findForThisPage('home');

        return $this->render('home.html.twig',['posts'=>$posts]);
    }

    /**
     * @Route("/contact", name="contact", methods="GET")
     * @return Response
     */
    public function contact() {
        $repository = $this->getDoctrine()
            ->getRepository(PagePost::class);
        $posts = $repository->findForThisPage('contact');
        return $this->render('home.html.twig',['posts'=>$posts]);
    }


    /**
     * @Route("/gallery", name="gallery", methods="GET")
     * @return Response
     */
    public function gallery() {
        $repository = $this->getDoctrine()
            ->getRepository(PagePost::class);
        $posts = $repository->findForThisPage('gallery');

        return $this->render('gallery.html.twig',['galleries'=>$posts]);
    }

    /**
     * @Route("/thoughts", name="thoughts", methods="GET")
     * @return Response
     */
    public function thoughts() {
        $repository = $this->getDoctrine()
            ->getRepository(PagePost::class);
        $posts = $repository->findForThisPage('thoughts');

        return $this->render('thoughts.html.twig',['posts'=>$posts]);
    }

    /**
     * @Route("/cv", name="cv", methods="GET")
     * @return Response
     */
    public function cv() {
        $repository = $this->getDoctrine()
            ->getRepository(PagePost::class);
        $posts = $repository->findForThisPage('cv');

        return $this->render('cv.html.twig',['posts'=>$posts]);
    }

    /**
     * @Route("/youtube/{category}/{page}", name="youtube_list", defaults={"page" = "1","category" = "dev"}, methods={"GET"})
     * @param int $page
     * @param YtCategories $category
     * @ParamConverter("category",options={"mapping": {"category" = "slug"}})
     * @return Response
     */
    public function youtube(int $page, YtCategories $category) {
        $per_page = 10;

        $categories = $this->getDoctrine()
            ->getRepository(YtCategories::class)
            ->getActiveCategories();

        $channels = $this->getDoctrine()
            ->getRepository(YtChannels::class)
            ->getChannelPage($page,$per_page, $category);

        $pages = $this->getDoctrine()
            ->getRepository(YtChannels::class)
            ->countPages($per_page,$category);

        $videos_repo = $this->getDoctrine()
            ->getRepository(YtVideos::class);

        $videos = [];

        foreach ($channels as $channel) {
            $videos[$channel->getId()] = $videos_repo->getLatestVideosQuery($channel,4,1)->getResult();
        }

        return $this->render('youtube.html.twig',[
            'paginator'=>[
                'page'=>$page,
                'pages'=>$pages,
                'base_route'=>'youtube_list'
            ],
            'channels'=>$channels,
            'categories'=>$categories,
            'current_category'=>$category,
            'videos'=>$videos
        ]);
    }



    /**
     * @Route("/youtube_channel_videos/{channel}/{page}", name="youtube_ajax_j" , condition="request.isXmlHttpRequest()", methods={"GET"})
     * @param int $page
     * @param YtChannels $channel
     * @ParamConverter("channel",options={"mapping":{"channel" = "chan_id"}})
     * @return Response
     */
    public function next4videos($page,YtChannels $channel) {
        $videos_repo = $this->getDoctrine()
            ->getRepository(YtVideos::class);
        $videos = $videos_repo->getLatestVideosQuery($channel,4,$page)->getArrayResult();
        if (count($videos)==0) return new Response('no results :(');
        $response = new JsonResponse();
//        var_dump($videos);
        $videos_json = $this->get('serializer')->serialize($videos,'json');
        $response->setData($videos_json);
        return $response;
        return $this->render('youtube_videos.html.twig',['videos'=>$videos,'channel'=>$channel]);
    }


    /**
     * @Route("/imdb/{page}", name="imdb_list", defaults={"page":"1"}, methods={"GET"})
     * @param $page
     * @return Response
     */
    public function imdb($page) {
        $imdb_repo = $this->getDoctrine()->getRepository(ImdbMovies::class);
        $movies = $imdb_repo->getMovies($page);
//        print_r($movies);
        return $this->render('imdb.html.twig',[
            'movies'=>$movies,
            'paginator'=>[
                'page'=>$page,
                'pages'=>$imdb_repo->countPages(),
                'base_route'=>'imdb_list'
            ]
        ]);
    }

}