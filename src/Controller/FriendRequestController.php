<?php

namespace App\Controller;

use App\Entity\FriendRequest;
use App\Enum\FriendRequestStatus;
use App\Repository\FriendRequestRepository;
use App\Services\FriendRequestService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/friend/request')]
class FriendRequestController extends BaseController
{
    #[Route('/', name: 'app_friend_request')]
    public function index(): Response
    {
        return $this->render('friend_request/index.html.twig', [
            'controller_name' => 'FriendRequestController',
        ]);
    }

    #[Route('/create', name: 'app_friend_request_create')]
    public function create(
        Request                 $request,
        FriendRequestRepository $repository,
        FriendRequestService    $service
    ): Response
    {
        $user = $this->security->getUser();

        $friendId = $request->get('friendId');

        if(!$friendId) {
            throw new Exception('Missing friendId');
        }

        $recipient = $repository->getRecipientById($friendId);
        $result = $service->checkIfAlreadySent($user, $recipient);
        
        if(!$result) {
            //TODO: Add logic for notifying the recipient
            $friendRequest = $repository->createFriendRequest($user, $friendId);
            
            return $this->render('friend_request/index.html.twig', [
                'controller_name' => 'FriendRequestController',
            ]);
        }

        $status = $result->getStatus();
        if($status == FriendRequestStatus::CANCELLED)
        {
            //TODO: Add logic for notifying the recipient
            $repository->deletePreviouslyCancelledFriendRequest($result);
            $friendRequest = $repository->createFriendRequest($user, $friendId);

            return $this->render('friend_request/index.html.twig', [
                'controller_name' => 'FriendRequestController',
            ]);
        }
       
        return $this->render('friend_request/index.html.twig', [
            'controller_name' => 'FriendRequestController',
            'status' => $status
        ]);
       
    }
}
