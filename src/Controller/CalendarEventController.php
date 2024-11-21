<?php

namespace App\Controller;

use App\Repository\CalendarEventRepository;
use App\Request\CalendarEventRequest;
use App\Services\CalendarEventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/calendar/event')]
class CalendarEventController extends AbstractController
{
    public function __construct(private Security $security)
    {
        
    }

    #[Route('/', name: 'app_calendar_event_get_all', methods: ['GET'])]
    public function index(): Response
    {        
        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
        ]);
    }

    #[Route('/create', name: 'app_calendar_event_create', methods: ['POST'])]
    public function createCalendarEvent(
        #[MapRequestPayload] CalendarEventRequest $request,        
        CalendarEventService $service,
        CalendarEventRepository $repository): Response
    {

        $end = new \DateTime($request->appointmentEnd);
        $start = new \DateTime($request->appointmentEnd);

        $user = $this->security->getUser();
        
        $repository->createCalendarEvent($user, $start, $end);


        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
        ]);
    }

    #[Route('/edit/{id}', name: 'app_calendar_event_edit', methods: ['PUT'])]
    public function editCalendarEvent(): Response
    {
        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
        ]);
    }

    #[Route('/delete', name: 'app_calendar_event_delete', methods: ['DELETE'])]
    public function deleteCalendarEvent(): Response
    {
        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
        ]);
    }
}
