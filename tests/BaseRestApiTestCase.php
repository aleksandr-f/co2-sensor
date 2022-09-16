<?php

declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseRestApiTestCase extends WebTestCase
{
    protected EntityManagerInterface $entityManager;

    protected readonly KernelBrowser $client;

    protected const REQUEST_DEFAULT_HEADERS = [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_ACCEPT' => 'application/json',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = static::getContainer()->get(id: EntityManagerInterface::class);

        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->entityManager->rollback();

        unset($this->entityManager);

        parent::tearDown();
    }
}
