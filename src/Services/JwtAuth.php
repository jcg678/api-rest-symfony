<?php
namespace App\Services;

use Firebase\JWT\JWT;
use App\Entity\User;

class JwtAuth{

    public $manager;

    public function __construct($manager){
        $this->manager = $manager;
    }

    public function signup(){
        return "probando servicio";
    }
}
