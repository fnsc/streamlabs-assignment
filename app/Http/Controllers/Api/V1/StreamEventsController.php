<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventRequest;
use App\Services\StreamEventsList\EventListInput;
use App\Services\StreamEventsList\StreamEventsListService;
use App\Services\UpdateEventStatus\UpdateEventStatusInput;
use App\Services\UpdateEventStatus\UpdateEventStatusService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class StreamEventsController extends Controller
{
    public function __construct(
        private readonly StreamEventsListService $eventsListService,
        private readonly UpdateEventStatusService $eventStatusService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getList(Request $request): JsonResponse
    {
        try {
            $status = $request->get('status', null);
            $perPage = $request->get('per_page', 100);
            $user = auth()->user();
            $input = new EventListInput($user, $status, $perPage);
            $events = $this->eventsListService->handle($input);

            return new JsonResponse($events);
        } catch (Exception $exception) {
            $this->logger->error(
                '[Api][V1][EventList] Something unexpected has happened',
                compact('exception')
            );

            return new JsonResponse([
                'error' => 'Something unexpected has happened',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(UpdateEventRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $input = new UpdateEventStatusInput(
                $user,
                $request->get('status'),
                $request->get('id'),
                $request->get('type')
            );

            $this->eventStatusService->handle($input);

            return new JsonResponse([], Response::HTTP_OK);
        } catch (Exception $exception) {
            $this->logger->error(
                '[Api][V1][UpdateStatus] Something unexpected has happened',
                compact('exception')
            );

            return new JsonResponse([
                'error' => 'Something unexpected has happened',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
