<?php
namespace App\Services;

use Firebase\JWT\JWT;
use App\Entity\User;

class JwtAuth{

    public $manager;
    public $key;

    public function __construct($manager){
        $this->manager = $manager;
        $this->key ='key_ejemplo';
    }

    public function signup($email, $password, $gettoken = null){
        $user = $this->manager->getRepository(User::class)->findOneBy([
            'email'=>$email,
            'password' => $password
        ]);

        $signup = false;
        if(is_object($user)){
            $signup = true;
        }

        if($signup){
            $token =[
              'sub' => $user->getId(),
              'name' => $user->getName(),
              'surname' => $user->getSurname(),
              'email' => $user->getEmail(),
              'iat' => time(),
              'exp' => time() +(7*24*60*60)
            ];
            $jwt = JWT::encode($token,$this->key, 'HS256');
            if($gettoken){
                $data = $jwt;
            }else{
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);
                $data = $decoded;
            }
        }else{
            $data = [
                'status'=>'error',
                'message' => 'login incorrecto'
            ];
        }

        return $data;


    }
}
