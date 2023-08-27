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
            $filters = $this->prepareFilters($request);
            $user = auth()->user();
            $input = new EventListInput($user, $filters['status'], $filters['page'], $filters['per_page']);
            $events = $this->eventsListService->handle($input);

            return new JsonResponse($events);
        } catch (Exception $exception) {
            $this->logger->error(
                '[Api][V1][EventList] Something unexpected has happened',
                compact('exception')
            );

            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
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

            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function prepareFilters(Request $request): array
    {
        return [
            'status' => $request->get('status', null),
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 100),
        ];
    }
}
