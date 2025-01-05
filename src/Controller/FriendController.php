<?php

namespace App\Controller;

use App\Repository\FriendRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/friend')]
class FriendController extends BaseController
{
    #[Route('/', name: 'app_friends', methods:'GET')]
    public function index(
        Request            $request,
        PaginatorInterface $paginator,
        FriendRepository   $repository
    ): Response
    {
        $user = $this->security->getUser();
        
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $friends = $repository->getFriends($user);
        
        $pagination = $paginator->paginate(
            $friends,
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 10)
        );

        return $this->render('friend/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/{id}/favorites', name: 'app_friends_add_to_favorites', methods: ['PUT', 'PATCH'])]
    public function addToFavorites(
        FriendRepository   $repository,
        int                $id
    ): Response
    {
        $user = $this->security->getUser();
        
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $friend = $repository->addToFavorites($user, $id);
        
        return $this->render('friend/index.html.twig', [
            'controller_name' => 'FriendController',
            'friend' => $friend
        ]);
    }
}
