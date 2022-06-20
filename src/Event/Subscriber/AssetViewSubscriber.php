<?php

namespace App\Event\Subscriber;

use App\Factory\AssetViewFactory;
use App\Repository\AssetRepository;
use App\Repository\AssetViewRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

class AssetViewSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly AssetViewRepository $assetViewRepository,
        private readonly AssetRepository $assetRepository,
        private readonly AssetViewFactory $assetViewFactory,
        private readonly Security $security
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {

        $request = $event->getRequest();
        $_route  = $request->attributes->get('_route');
        $params = $request->attributes->get('_route_params');

        if($_route == 'app_asset_show'){

            $asset = $this->assetRepository->findOneBy(['id' => $params['id']]);

            if($asset){
                $assetView = $this->assetViewFactory->create($asset, $this->security->getUser());
                $this->assetViewRepository->add($assetView, true);
            }
        }

    }
}
