<?php

namespace App\Serializer;

use App\Entity\Asset;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Twig\Environment;

class AssetNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
        private Environment $twig
    ) {
    }


    /** @param Asset $asset
     * @return array */
    public function normalize(
        mixed $asset,
        string $format = null,
        array $context = []
    ): array {

        $data = $this->objectNormalizer->normalize($asset, 'json', $context);

        return [
            'type' => 'Feature',
            'geometry' => [
                'type' => "Point",
                'latitude' => $asset->getLatitude(),
                'longitude' => $asset->getLongitude(),
                'coordinates' => [$asset->getLongitude(), $asset->getLatitude()]
            ],
            'properties' => [
                'id' => $asset->getId()->toRfc4122(),
                'title' => $asset->getTitle(),
                'sqm' => $asset->getSqm(),
                'fee' => $asset->getFee(),
                'html' => $this->twig->render('asset/_map-asset-preview.html.twig', ['asset' => $asset]),
            ]
        ];

    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Asset;
    }
}
