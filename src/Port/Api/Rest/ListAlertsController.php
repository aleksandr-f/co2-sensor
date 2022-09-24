<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

use App\Application\AlertReadRepositoryInterface;
use App\Application\ListAlertsQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ListAlertsController extends AbstractController
{
    public function __construct(
        private readonly AlertReadRepositoryInterface $alertReadRepository,
    ) {
    }

    #[Route(
        path: '/{sensorId}/alerts',
        methods: ['GET'],
    )]
    /**
     * @ParamConverter(
     *     "request",
     *     converter="param_converter.query",
     *     class="App\Port\Api\Rest\ListAlertsRequest"
     * )
     */
    public function create(string $sensorId, ListAlertsRequest $request): Response
    {
        $listAlertsQuery = new ListAlertsQuery(
            sensorId: $sensorId,
            limit: $request->limit,
            offset: $request->offset,
        );

        $alerts = $this->alertReadRepository->findByListAlertsQuery(
            query: $listAlertsQuery,
        );

        return $this->json(data: ListAlertsResponse::createFromArray(alerts: $alerts));
    }
}
