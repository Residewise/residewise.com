<?php

namespace App\Event\Subscriber;

use App\Entity\Insight;
use App\Repository\InsightRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

class AssetPageViewSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private Security $security,
        private InsightRepository $insightRepository
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $insight = $this->createPageView($event);
            $this->insightRepository->add($insight);
        }
    }

    private function createPageView(RequestEvent $event): Insight
    {
        $request = $event->getRequest();

        $insight = new Insight();
        $insight->setAction('page-view');
        $insight->setPath($request->getRequestUri());

        $user = $this->security->getUser();
        if ($user !== null) {
            $insight->setOwner($user);
        }

        return $insight;
    }
}
