<?php declare(strict_types=1);

namespace Test;

use App\InnNumber\InnNumber;
use App\TaxPayer\Api;
use Codeception\Test\Unit;
use Exception;
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
        $client->addResponse($this->createResponse(200, ['status' => true]));
        $api = $this->createApiInstance($client);
        $result = $api->getByInn(new InnNumber(self::VALID_INN));
        $this->assertTrue($result->isPayTaxes());
    }

    public function testGetByInnIsInvalid(): void
    {
        $client = new Client();
        $client->addResponse($this->createResponse(200, ['status' => false]));
        $api = $this->createApiInstance($client);
        $result = $api->getByInn(new InnNumber(self::VALID_INN));
        $this->assertFalse($result->isPayTaxes());
    }

    public function testGetByInnInvalidResponse(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        $client = new Client();
        $client->addResponse($this->createResponse(500));
        $api = $this->createApiInstance($client);
        $api->getByInn(new InnNumber(self::VALID_INN));
    }

    public function getByInnInvalidResponseBodyDataProvider(): array
    {
        return [
            [[]],
            [['status' => 'false']]
        ];
    }

    /**
     * @dataProvider getByInnInvalidResponseBodyDataProvider
     * @param array $body
     * @throws Exception
     */
    public function testGetByInnInvalidResponseBody(array $body): void
    {
        $this->expectException(Exception::class);
        $client = new Client();
        $client->addResponse($this->createResponse(200, $body));
        $api = $this->createApiInstance($client);
        $api->getByInn(new InnNumber(self::VALID_INN));
    }

    private function createResponse(int $status, array $data = []): ResponseInterface
    {
        return new Response($status, [], json_encode($data, JSON_THROW_ON_ERROR));
    }

    private function createApiInstance(Client $client): Api
    {
        return new Api(
            $client,
            $this->tester->grabService(RequestFactoryInterface::class),
            $this->tester->grabService(StreamFactoryInterface::class),
        );
    }
}
