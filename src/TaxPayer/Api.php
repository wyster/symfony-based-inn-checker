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

    public function getTaxpayerStatus(int $inn): array
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

        // @todo check response fields and types
        return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
    }
}
