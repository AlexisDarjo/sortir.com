<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Serie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{

    #[Route('/details/{id}', name: '_details', requirements: ['id' => '\d+'])]
    public function details(Participant $participant): Response

    {

        return $this->render('user/detail.html.twig', [
            'participant' => $participant
        ]);
    }
}
