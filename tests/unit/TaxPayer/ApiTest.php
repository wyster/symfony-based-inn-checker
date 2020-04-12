<?php declare(strict_types=1);

namespace Test;

use App\TaxPayer\Api;
use Codeception\Test\Unit;
use Http\Mock\Client;
use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ApiTest extends Unit
{
    private const VALID_INN = 366221019350;
    private const INVALID_INN = 366221019361;

    protected UnitTester $tester;

    public function testGetByInnIsValid(): void
    {
        $client = new Client();
        $client->addResponse($this->createResponse(true));

        $api = new Api(
            $client,
            $this->tester->grabService(RequestFactoryInterface::class),
            $this->tester->grabService(StreamFactoryInterface::class),
        );

        $result = $api->getByInn(self::VALID_INN);
        $this->assertTrue($result->isPayTaxes());
    }

    public function testGetByInnIsInValid(): void
    {
        $client = new Client();
        $client->addResponse($this->createResponse(true));

        $api = new Api(
            $client,
            $this->tester->grabService(RequestFactoryInterface::class),
            $this->tester->grabService(StreamFactoryInterface::class),
        );

        $result = $api->getByInn(self::VALID_INN);
        $this->assertTrue($result->isPayTaxes());
    }

    private function createResponse(bool $status): ResponseInterface
    {
        return new Response(200, [], json_encode(['status' => $status], JSON_THROW_ON_ERROR));
    }
}
