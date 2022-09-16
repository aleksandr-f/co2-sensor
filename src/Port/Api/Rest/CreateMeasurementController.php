<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

use App\Application\CreateMeasurementCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CreateMeasurementController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    #[Route(
        path: '/{sensorId}/measurements',
        methods: ['POST'],
    )]
    /**
     * @ParamConverter(
     *     "request",
     *     converter="fos_rest.request_body",
     *     class="App\Port\Api\Rest\CreateMeasurementRequest"
     * )
     */
    public function create(string $sensorId, CreateMeasurementRequest $request): Response
    {
        $this->commandBus->dispatch(
            message: new CreateMeasurementCommand(
                sensorId: $sensorId,
                co2: $request->co2,
                time: $request->time,
            ),
        );

        return new Response(status: Response::HTTP_CREATED);
    }
}
