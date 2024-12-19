<?php

namespace App\Controller;

use App\Repository\CalendarEventRepository;
use App\Request\CalendarEventRequest;
use App\Services\CalendarEventService;
use Exception;
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

    #[Route('/{id}', name: 'app_calendar_event_get_one', methods: ['GET'])]
    public function get(
        CalendarEventRepository $repository,
        int $id
    ): Response
    {        
        $user = $this->security->getUser();

        $event = $repository->getCalendarEvent($user, $id);

        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
            'calendar_event' => $event
        ]);
    }

    #[Route('/create', name: 'app_calendar_event_create', methods: ['POST'])]
    public function createCalendarEvent(
        #[MapRequestPayload] CalendarEventRequest $request,        
        CalendarEventService $service,
        CalendarEventRepository $repository): Response
    {

        $start = new \DateTime($request->appointmentStart);
        $end = new \DateTime($request->appointmentEnd);
        $title = $request->appointmentTitle;
        $description = $request->appointmentDescription ?? null;


        $correctDate = $service->appointmentTimeChecker($start, $end);
        if(!$correctDate) {
            throw new Exception('Appointment end cannot be equal or earlier to appointment start');
        }

        $user = $this->security->getUser();
        
        $event = $repository->createCalendarEvent($user, $start, $end, $title, $description);


        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
            'calendar_event' => $event
        ]);
    }

    #[Route('/edit/{id}', name: 'app_calendar_event_edit', methods: ['PUT'])]
    public function editCalendarEvent(
        #[MapRequestPayload] CalendarEventRequest $request,        
        CalendarEventService $service,
        CalendarEventRepository $repository,
        int $id
        ): Response
    {
        $start = new \DateTime($request->appointmentStart);
        $end = new \DateTime($request->appointmentEnd);
        $title = $request->appointmentTitle;
        $description = $request->appointmentDescription ?? null;

        $correctDate = $service->appointmentTimeChecker($start, $end);
        if(!$correctDate) {
            throw new Exception('Appointment end cannot be equal or earlier to appointment start');
        }

        $user = $this->security->getUser();

        $event = $repository->editCalendarEvent($user, $id, $start, $end, $title, $description);
        
        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
            'calendar_event' => $event
        ]);
    }

    #[Route('/delete/{id}', name: 'app_calendar_event_delete', methods: ['DELETE'])]
    public function deleteCalendarEvent(
        CalendarEventRepository $repository,
        int $id
    ): Response
    {
        $user = $this->security->getUser();
        $repository->deleteCalendarEvent($user, $id);

        return $this->render('calendar_event/index.html.twig', [
            'controller_name' => 'CalendarEventController',
        ]);
    }
}
