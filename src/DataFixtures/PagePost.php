<?php

namespace App\DataFixtures;

use App\Entity\PagePost as PagePost2;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PagePost extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $pages = [
            [true,'contact','hello there',1],
            [true,'contact','welcome to my contact page',2],
            [true,'contact','try my email or facebook !',3],
            [true,'home','welcome to my homepage',1],
            [true,'home','hej nozownik !',2],
            [false,'home','i co teraz cholero ? :P',2],
            [true,'home','try to find something fun to do',3],
            [true,'gallery','welcome to my gallery',1],
            [true,'gallery','here are just some of the sites that i worked on',2],
            [true,'gallery','some are dead and gone (as these things go)',3],
            [true,'gallery','i tried providing alternative links to those (from the internet archive)',4],
        ];
        foreach ($pages as $pagearr) {
            $page = new PagePost2();
            $page->setActive($pagearr[0]);
            $page->setPage($pagearr[1]);
            $page->setBody($pagearr[2]);
            $page->setPostOrder($pagearr[3]);
            $manager->persist($page);
        }

        $manager->flush();
    }
}
