<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

use JetBrains\PhpStorm\Pure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class QueryParamConverter implements ParamConverterInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $object = $this->serializer->denormalize(
            data: $request->query->all(),
            type: $configuration->getClass(),
            context: [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true],
        );

        $request->attributes->set(
            key: $configuration->getName(),
            value: $object,
        );

        return true;
    }

    #[Pure]
    public function supports(ParamConverter $configuration): bool
    {
        return 'param_converter.query' === $configuration->getConverter() && $configuration->getClass();
    }
}
