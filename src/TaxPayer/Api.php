<?php declare(strict_types=1);

namespace App\TaxPayer;

use Exception;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Api implements ApiInterface
{
    private const URL = 'https://statusnpd.nalog.ru/api/v1/tracker/taxpayer_status';

    private ClientInterface $httpClient;
    private RequestFactoryInterface $httpRequestFactory;
    private StreamFactoryInterface $streamFactory;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $httpRequestFactory, StreamFactoryInterface $streamFactory)
    {
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function getByInn(int $inn): TaxPayerEntityInterface
    {
        $request = $this->httpRequestFactory->createRequest('POST', self::URL);
        $params = [
            'inn' => $inn,
            'requestDate' => date('Y-m-d')
        ];

        $request = $request->withHeader('Content-Type', 'application/json');
        $bodyStream = $this->streamFactory->createStream(json_encode($params, JSON_THROW_ON_ERROR));
        $request = $request->withBody($bodyStream);

        $response = $this->httpClient->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Invalid response', $response->getStatusCode());
        }
        $responseBody = $response->getBody()->__toString();
        $result = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        $this->validateResponse($result);

        $entity = new TaxPayerEntity();
        $entity->setPayTaxes($result['status']);

        return $entity;
    }

    private function validateResponse(array $data): void
    {
        if (!array_key_exists('status', $data)) {
            throw new Exception('Status not exists in response data');
        }

        if (!is_bool($data['status'])) {
            throw new Exception('Status have invalid type');
        }
    }
}
