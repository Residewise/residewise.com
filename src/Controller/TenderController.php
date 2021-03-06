<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Tender;
use App\Form\TenderFormType;
use App\Repository\AssetRepository;
use App\Repository\TenderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/auction')]
class TenderController extends AbstractController
{
    public function __construct(
        private readonly TenderRepository $tenderRepository,
        private readonly AssetRepository $assetRepository,
    ) {
    }

    #[Route(path: '/new/{id}', name: 'new_tender', methods: ['GET', 'POST'])]
    public function create(Asset $asset, Request $request): Response
    {
        $tender = new Tender();
        $form = $this->createForm(TenderFormType::class, $tender, [
            'asset' => $asset,
        ]);
         $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($this->assetHasActiveTender($asset)){
                $this->addFlash('message', 'tender already active');

                return $this->redirectToRoute('app_asset_show', [
                    'id' => $asset->getId(),
                ]);
            }

            $asset->setTender($tender);

            $minimumBid = (string)$form->get('minimumBid')
                ->getData();
            if ($minimumBid !== '' && $minimumBid !== '0') {
                $asset->setPrice($minimumBid);
            }

            $tender->setStartAt($form->get('startAt')->getData());
            $tender->setEndAt($form->get('startAt')->getData());
            $tender->setAsset($asset);
            $tender->setBid(null);

            $this->tenderRepository->add($tender, true);
            $this->assetRepository->add($asset);

            return $this->redirectToRoute('app_asset_show', [
                'id' => $asset->getId(),
            ]);

        }

        return $this->render('asset/tender/new.html.twig', [
            'tenderForm' => $form->createView(),
            'asset' => $asset,
        ]);
    }

    private function assetHasActiveTender(Asset $asset): bool
    {
        /** @var Tender $tender */
        foreach ($asset->getTenders() as $tender){
            if($tender->getIsActive()){
                return true;
            }
        }

        return false;
    }
}
