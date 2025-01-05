<?php

namespace App\Controller;

use App\Entity\FriendRequest;
use App\Enum\FriendRequestStatus;
use App\Repository\FriendRequestRepository;
use App\Services\FriendRequestService;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
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

    #[Route('/create', name: 'app_friend_request_create', methods: 'POST')]
    public function create(
        Request                 $request,
        FriendRequestRepository $repository,
        FriendRequestService    $service
    ): Response
    {
        $user = $this->security->getUser();
        
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $friendId = $request->get('friendId');

        if(!$friendId) {
            throw new Exception('Missing friendId');
        }

        $recipient = $repository->getRecipientById($friendId);
        $result = $service->checkIfAlreadySent($user, $recipient);
        
        if(!$result) {
            //TODO: Add logic for notifying the recipient
            $friendRequest = $repository->createFriendRequest($user, $recipient);
            
            return $this->render('friend_request/index.html.twig', [
                'controller_name' => 'FriendRequestController',
            ]);
        }

        $status = $result->getStatus();
        if($status == FriendRequestStatus::CANCELLED)
        {
            //TODO: Add logic for notifying the recipient
            $repository->deletePreviouslyCancelledFriendRequest($result);
            $friendRequest = $repository->createFriendRequest($user, $recipient);

            return $this->render('friend_request/index.html.twig', [
                'controller_name' => 'FriendRequestController',
            ]);
        }
       
        return $this->render('friend_request/index.html.twig', [
            'controller_name' => 'FriendRequestController',
            'status' => $status
        ]);
       
    }

    #[Route('/sent/pending', name: 'app_friend_request_get_all_sent_pending', methods: 'GET')]
    public function getSentPending(
        Request                 $request,
        PaginatorInterface      $paginator,
        FriendRequestRepository $repository
        )
    {
        $user = $this->security->getUser();
        
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $sentFriendRequests = $repository->getAllSentFriendRequests($user, FriendRequestStatus::PENDING);
        
        $pagination = $paginator->paginate(
            $sentFriendRequests,
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 10)
        );

        return $this->render('friend_request/sent_pending_list.html.twig', [
            'pagination' => $pagination,
        ]);

    }

    #[Route('/recieved/pending', name: 'app_friend_request_get_all_recieved_pending', methods: 'GET')]
    public function getRecievedPending(
        Request                 $request,
        PaginatorInterface      $paginator,
        FriendRequestRepository $repository
        )
    {
        $user = $this->security->getUser();
        
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $recievedFriendRequests = $repository->getAllRecievedFriendRequests($user, FriendRequestStatus::PENDING);
        
        $pagination = $paginator->paginate(
            $recievedFriendRequests,
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 10)
        );

        return $this->render('friend_request/recieved_pending_list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/accept/{id}', name: 'app_friend_request_accept', methods: ['PUT', 'PATCH'])]
    public function accept(
        FriendRequestService    $service,
        int                     $id
    ): Response
    {
        $user = $this->security->getUser();
        
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $friendRequest = $service->acceptFriendRequest($user, $id);

        return $this->render('friend_request/index.html.twig', [
            'controller_name' => 'FriendRequestController',
        ]);
    }

    //TODO: add route for cancel and decline
}
