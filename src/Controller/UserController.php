<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Video;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    private function resjson($data){
        $json = $this->get('serializer')->serialize($data, 'json');
        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function index()
    {
        $user_repo= $this->getDoctrine()->getRepository(User::class);
        $video_repo = $this->getDoctrine()->getRepository(Video::class);

        $users=$user_repo->findAll();
        $user = $user_repo->find(1);
        $videos= $video_repo->findAll();

        /*foreach ($users as $user){
                echo "<h1>{$user->getName()} {$user->getSurname()}</h1>";
                foreach ($user->getVideos() as $video){
                    echo "<h2>{$video->getTitle()}</h2>";
                }
            }

        foreach ($videos as $video){
            echo "<h1>{$video->getTitle()} {$video->getUser()->getEmail()}</h1>";

        }*/
        $data = [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ];

        //die();
        return $this->resjson($data);
    }
}
