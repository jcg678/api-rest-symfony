<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Video;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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


        $data = [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ];


        return $this->resjson($data);
    }

    public function create(Request $request){
        $json = $request->get('json', null);

        $params = json_decode($json);

        $data = [
            'status'=>'error',
            'code'  => 200,
            'message'=> 'El usuario no se ha creado',
            'json' => $params
        ];

        return new JsonResponse($data);
    }
}
