<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends Controller
{
    /**
     * @Route("/", name="participant")
     */
    public function index()
    {
        return $this->render('participant/index.html.twig', [
            'page_name'=>'Home',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(){

        $participant = new Participant();
        $loginForm = $this->createForm(LoginType::class,$participant);

        return $this->render('participant/login.html.twig', [
            'loginForm'=>$loginForm->createView(),
            'page_name'=>'Login',
        ]);
    }
}
