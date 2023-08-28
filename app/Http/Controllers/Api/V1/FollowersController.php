<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Followers\FollowersInput;
use App\Services\Followers\FollowersService;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class FollowersController extends Controller
{
    public function __construct(
        private readonly FollowersService $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getFollowerCountLast30Days(): JsonResponse
    {
        try {
            $user = auth()->user();
            $input = new FollowersInput($user, new DateTime());
            $result = $this->service->handle($input);

            return new JsonResponse([
                'followers_count' => $result
            ]);
        } catch (Exception $exception) {
            $this->logger->error(
                '[Api][V1][Followers] Something unexpected has happened',
                compact('exception')
            );

            return new JsonResponse([
                'error' => 'Something unexpected has happened.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
