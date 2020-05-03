<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Video;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use App\Services\JwtAuth;

class VideoController extends AbstractController
{

    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/VideoController.php',
        ]);
    }

    private function resjson($data){
        $json = $this->get('serializer')->serialize($data, 'json');
        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function create(Request $request, JwtAuth $jwtAuth){
        $data = [
            'status'=>'error',
            'code'=>400,
            'message'=>'El video no ha podido crearse'
        ];

        $token = $request->headers->get('Authorization');
        $authCheck= $jwtAuth->checkToken($token);

        if($authCheck){
            $json = $request->get('json',null);
            $params = json_decode($json);

            $identity = $jwtAuth->checkToken($token,true);


            if(!empty($json)){
                $user_id = ($identity->sub != null) ? $identity->sub : null;
                $title = (!empty($params->title)) ? $params->title : null;
                $description = (!empty($params->description)) ? $params->description : null;
                $url=(!empty($params->url)) ? $params->url : null;
                if(!empty($user_id)  && !empty($title)){
                    $em = $this->getDoctrine()->getManager();
                    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$user_id]);

                    $video = new Video();
                    $video->setUser($user);
                    $video->setTitle($title);
                    $video->setDescription($description);
                    $video->setUrl($url);
                    $video->setStatus('normal');

                    $dateNow = new \DateTime('now');
                    $video->setCreatedAt($dateNow);
                    $video->setUpdatedAt($dateNow);

                    $em->persist($video);
                    $em->flush();

                    $data = [
                        'status'=>'success',
                        'code'=>200,
                        'message'=>'El video se ha guardado',
                        'video' => $video
                    ];

                }
            }
        }


        return $this->resjson($data);
    }

    public function videos(Request $request, JwtAuth $jwtAuth){
        $data =[
            'status'=>'error',
            'code'=> 404,
            'message'=>'No se pueden listar videos'
        ];

        return $this->resjson($data);
    }

}
