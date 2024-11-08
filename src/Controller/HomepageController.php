<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(Security $security): Response
    {
        $values = [
            'controller_name' => 'HomepageController',
        ];

        $username = $security->getUser()?->getUsername();

        if(!empty($username)) {
            $values['username'] = $username;
        }

        return $this->render('homepage/index.html.twig',  $values);
    }
}
