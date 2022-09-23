<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

use App\Application\MeasurementReadRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetSensorMetricsController extends AbstractController
{
    public function __construct(
        private readonly MeasurementReadRepositoryInterface $measurementReadRepository,
    ) {
    }

    #[Route(
        path: '/{sensorId}/metrics',
        methods: ['GET'],
    )]
    public function create(string $sensorId): Response
    {
        $getSensorMetricsResponse = new GetSensorMetricsResponse(
            maxLast30Days: $this->measurementReadRepository->getMax(sensorId: $sensorId, days: 30),
            avgLast30Days: $this->measurementReadRepository->getAvg(sensorId: $sensorId, days: 30),
        );

        return $this->json(data: $getSensorMetricsResponse);
    }
}
