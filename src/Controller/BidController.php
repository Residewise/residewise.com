<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Bid;
use App\Entity\Tender;
use App\Form\BidFormType;
use App\Repository\BidRepository;
use App\Repository\TenderRepository;
use App\Service\ExchangeRateService\ExchangeRateService;
use App\Service\RegionalSettingsService\RegionalSettingsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route(path: '/bid')]
class BidController extends AbstractController
{
    public function __construct(
        private readonly BidRepository $bidRepository,
        private readonly TenderRepository $tenderRepository,
        private readonly HubInterface $hub,
        private readonly RegionalSettingsService $regionalSettingsService,
        private readonly ExchangeRateService $exchangeRateService
    )
    {
    }

    #[Route(path: '/new/{id}', name: '_new_bid', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function _create(Asset $asset, Request $request): Response
    {
        $suggestedBidAmount = $this->resolveBidSuggestion($asset);

        $exchangedSuggestedBid = null;
        if ($this->regionalSettingsService->getCurrency() !== $asset->getCurrency()) {
            $xe = $this->exchangeRateService->exchange(
                from: $asset->getCurrency(),
                to: $this->regionalSettingsService->getCurrency(),
                amount: $suggestedBidAmount
            );
            $exchangedSuggestedBid = $xe['to'][0]['mid'];
        }

        $bid = new Bid();
        $form = $this->createForm(BidFormType::class, $bid, [
            'suggested_bid_amount' => $suggestedBidAmount,
            'asset' => $asset,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $price = $form->get('price')
                ->getData();
            $currency = $this->regionalSettingsService->getCurrency();

            $bid->setOwner($this->getUser());
            $bid->setPrice((string)$price);
            $bid->setCurrency($asset->getCurrency());
            $this->bidRepository->add($bid, true);
            $tender = $asset->getTender();
            assert($tender instanceof Tender);
            $this->resolveTenderBidSuggestion($bid, $tender);

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            $this->hub->publish(new Update('tender', $this->renderView('/asset/bid/bid.stream.html.twig', [
                'tender' => $asset->getTender(),
            ])));
            $this->hub->publish(new Update('tender-price', $this->renderView('/asset/price.stream.html.twig', [
                'asset' => $asset,
            ])));

            return $this->redirectToRoute('_new_bid', [
                'id' => $asset->getId(),
            ]);
        }

        return $this->render('asset/bid/_form.html.twig', [
            'asset' => $asset,
            'bidForm' => $form->createView(),
            'exchangedSuggestedBid' => $exchangedSuggestedBid,
        ]);
    }

    protected function addPercentageToNumber(null|string $number, float $percentage): string
    {
        $amount = floatval($number);

        return number_format(((($amount / 100) * $percentage) + $amount), 2, ',');
    }

    private function resolveTenderBidSuggestion(Bid $bid, Tender $tender): void
    {
        if ($tender->getBid() === null || $bid->getPrice() > $tender->getBid()->getPrice()) {
            $tender->setBid($bid);
            $this->tenderRepository->add($tender, true);
        }
    }

    private function resolveBidSuggestion(Asset $asset): bool|string
    {
        return match ($asset->getTerm()) {
            'rent' => $this->resolveRentalBidSuggestion($asset),
            'sale' => $this->resolveSaleBidSuggestion($asset),
            default => false,
        };
    }

    private function resolveSaleBidSuggestion(Asset $asset): string
    {
        return match ($asset->getTender()?->getBid()) {
            null => $this->addPercentageToNumber($asset->getPrice(), 0.05),
            default => $this->addPercentageToNumber($asset->getTender()->getBid()->getPrice(), 0.05),
        };
    }

    private function resolveRentalBidSuggestion(Asset $asset): string
    {
        return match ($asset->getTender()?->getBid()) {
            null => $this->addPercentageToNumber($asset->getPrice(), 0.5),
            default => $this->addPercentageToNumber($asset->getTender()->getBid()->getPrice(), 0.5),
        };
    }
}
