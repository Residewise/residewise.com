<?php

namespace App\Controller;

use App\Form\RegionFormType;
use App\Service\RegionalSettingsService\RegionalSettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/region')]
class RegionController extends AbstractController
{

    public function __construct(
        private readonly RegionalSettingsService $regionalSettingsService
    ) {
    }

    #[Route(path: '/', name: '_app_regional_settings', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $regionForm = $this->createForm(RegionFormType::class, null, [
            'request' => $request,
        ]);
        $regionForm->handleRequest($request);

        if ($regionForm->isSubmitted() && $regionForm->isValid()) {

            $locale = $regionForm->get('language')->getData();
            $region = $regionForm->get('region')->getData();
            $timezone = $regionForm->get('timezone')->getData();
            $currency = $regionForm->get('currency')->getData();

            $this->regionalSettingsService->configureLocale($locale);
            $this->regionalSettingsService->configureRegion($region);
            $this->regionalSettingsService->configureTimezone($timezone);
            $this->regionalSettingsService->configureCurrency($currency);

            return $this->redirectToRoute('app_asset_index');
        }

        return $this->render('region/index.html.twig', [
            'regionForm' => $regionForm->createView(),
        ]);
    }

}
