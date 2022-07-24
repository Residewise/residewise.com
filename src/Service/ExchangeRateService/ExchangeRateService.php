<?php

declare(strict_types = 1);

namespace App\Service\ExchangeRateService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private const XE_BASE_PATH = 'https://xecdapi.xe.com/v1/convert_from.json';

    public function __construct(
        private HttpClientInterface $xeApiClient,
    )
    {
    }

    public function exchange(string $from, string $to, mixed $amount): mixed
    {
       $response = $this->xeApiClient->request(Request::METHOD_GET, self::XE_BASE_PATH, [
        'query' => [
            'from' => $from,
               'to' => $to,
               'amount' => $amount,
               'decimal_places' => 2,
        ],
    ]);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->decode($response->getContent(), 'json');
    }
}