<?php

namespace App\Event\Subscriber;

use App\Service\RegionalSettingsService\RegionalSettingsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use function select;

class RegionalSettingsSubscriber implements EventSubscriberInterface
{

    public final const LOCALE = 'en';
    public final const CURRECNY = 'eur';
    public final const REGION = 'de';
    public final const TIMEZONE = 'UTC';

    public function __construct(
        private readonly RegionalSettingsService $regionalSettingsService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onKernelRequest', 100]
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $locale = $event->getRequest()->getSession()->get('_locale') ?? self::LOCALE;
        $region = $event->getRequest()->getSession()->get('_region') ?? self::REGION;
        $timezone = $event->getRequest()->getSession()->get('_timezone') ?? self::TIMEZONE;
        $currency = $event->getRequest()->getSession()->get('_currency') ?? self::CURRECNY;

        $this->regionalSettingsService->resolveRegionalSettings(
            locale: $locale,
            region: $region,
            currency: $currency,
            timezone: $timezone
        );
    }

}
