<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

use App\Application\SensorNotFoundException;
use App\Application\SensorReadRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetSensorController extends AbstractController
{
    public function __construct(
        private readonly SensorReadRepositoryInterface $sensorReadRepository,
    ) {
    }

    #[Route(
        path: '/{sensorId}',
        methods: ['GET'],
    )]
    public function create(string $sensorId): Response
    {
        try {
            $sensor = $this->sensorReadRepository->getAsArray(
                id: $sensorId,
            );
        } catch (SensorNotFoundException $exception) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $getSensorResponse = new GetSensorResponse(
            status: $sensor['status'],
        );

        return $this->json(data: $getSensorResponse);
    }
}
