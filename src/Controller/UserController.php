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
        ];

        if($json != null){
            $name = (!empty($params->name)) ? $params->name : null;
            $surname = (!empty($params->surname)) ? $params->surname : null;
            $email = (!empty($params->email)) ? $params->email : null;
            $password = (!empty($params->password)) ? $params->password : null;

            $validator = Validation::createValidator();
            $validate_email = $validator->validate($email,[
               new Email()
            ]);

            if(!empty($email) && count($validate_email)== 0 && !empty($password) && !empty($name) && !empty($surname)){
                $data = [
                    'status'=>'success',
                    'code'  => 200,
                    'message'=> 'Validacion correcta',
                ];
            }else{
                $data = [
                    'status'=>'success',
                    'code'  => 200,
                    'message'=> 'Validacion incorrecta',
                ];
            }
        }

        return new JsonResponse($data);
    }
}
