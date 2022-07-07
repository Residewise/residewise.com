<?php

declare(strict_types = 1);

namespace App\Service\RegionalSettingsService;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;
use Twig\Extension\CoreExtension;

final class RegionalSettingsService
{
    private string $locale;

    private string $currency = 'eur';

    private float $multiplier = 0;

    private string $region = 'de';

    private string $timezone = 'UTC';

    public function __construct(
        private readonly Environment $environment,
        private readonly RequestStack $requestStack,
        private readonly HttpClientInterface $client,
        private readonly LocaleSwitcher $localeSwitcher
    ) {
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getCurrency(): string
    {
        return strtoupper($this->currency);
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getMultiplier(): float|int
    {
        return $this->multiplier;
    }

    public function setMultiplier(float|int $multiplier): void
    {
        $this->multiplier = $multiplier;
    }

    public function getExchangeRates(): array
    {
        // TODO: add service to get ecb exchange rates and parse the xml
    }

    public function resolveRegionalSettings(string $locale, string $region, string $currency, string $timezone) {
        $this->configureLocale($locale);
        $this->configureRegion($region);
        $this->configureCurrency($currency);
        $this->configureTimezone($timezone);
    }

    public function configureLocale(string $locale)
    {
        $this->setLocale($locale);
        $this->localeSwitcher->setLocale($locale);
        $this->requestStack->getSession()
            ->set('_locale', $locale);
    }

    public function configureCurrency(string $currency)
    {
        $this->setCurrency($currency);
        $this->requestStack->getSession()
            ->set('_currency', $currency);
    }

    public function configureTimezone(string $timezone)
    {
        $this->setTimezone($timezone);
        $this->requestStack->getSession()
            ->set('_timezone', $timezone);
        $this->environment->getExtension(CoreExtension::class)
            ->setTimezone($timezone);
    }

    public function configureRegion(string $region)
    {
        $this->setRegion($region);
        $this->requestStack->getSession()
            ->set('_region', $region);
    }
}
