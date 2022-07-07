<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Image;
use App\Form\AssetSearchFormType;
use App\Form\AssetType;
use App\Repository\AssetRepository;
use App\Repository\ImageRepository;
use App\Service\ImageUploadService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/properties')]
class AssetController extends AbstractController
{
    public function __construct(
        private readonly AssetRepository $assetRepository,
        private readonly ImageRepository $imageRepository,
        private readonly SerializerInterface $serializer,
        private readonly ImageUploadService $imageUploadService,
    ) {
    }

    #[Route(path: '/', name: 'app_asset_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(AssetSearchFormType::class);
        $searchForm->handleRequest($request);
        $assets = $this->assetRepository->findAll();

        $json = new JsonResponse($this->serializer->serialize($assets, 'json', [
            'groups' => 'asset_map',
        ]));

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $assets = $this->handleSearchForm($request, $searchForm);
            $json = new JsonResponse($this->serializer->serialize($assets, 'json', [
                'groups' => 'asset_map',
            ]));

            return $this->render('asset/index.html.twig', [
                'features' => $json->getContent(),
                'assets' => $assets,
                'searchForm' => $searchForm->createView(),
            ]);

        }

        return $this->render('asset/index.html.twig', [
            'features' => $json->getContent(),
            'assets' => $assets,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route(path: '/new', name: 'app_asset_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request): Response
    {
        $asset = new Asset();
        $asset->setOwner($this->getUser());
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var array<UploadedFile> $files */
            $files = $form->get('images')
                ->getData();

            foreach ($files as $file) {
                $info = getimagesize($file);
                [$x, $y] = $info;

                $fileContentBase64 = $this->imageUploadService->process($file, 800, true);
                $image = new Image($fileContentBase64, $asset);
                $image->setHeight($y);
                $image->setWidth($x);
                $this->imageRepository->add($image);
            }

            $this->assetRepository->add($asset);

            return $this->redirectToRoute('user_assets');
        }

        return $this->renderForm('asset/new.html.twig', [
            'asset' => $asset,
            'form' => $form,
        ]);
    }

    #[Route(path: '/images/{id}', name: 'asset_images', methods: ['GET'])]
    public function images(Asset $asset): Response
    {
        return $this->render('asset/images/show.html.twig', [
            'asset' => $asset,
        ]);
    }

    #[Route('/show/{id}', name: 'app_asset_show', methods: ['GET'])]
    public function show(Asset $asset): Response
    {

        return $this->render('asset/show.html.twig', [
            'asset' => $asset,
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'app_asset_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Asset $asset, AssetRepository $assetRepository): Response
    {
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $assetRepository->add($asset);

            return $this->redirectToRoute('app_asset_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset/edit.html.twig', [
            'asset' => $asset,
            'form' => $form,
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'app_asset_delete', methods: ['POST'])]
    public function delete(Request $request, Asset $asset, AssetRepository $assetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $asset->getId(), (string)$request->request->get('_token'))) {
            $assetRepository->remove($asset);
        }

        return $this->redirectToRoute('app_asset_index', [], Response::HTTP_SEE_OTHER);
    }

    private function handleSearchForm(Request $request, FormInterface $searchForm): mixed
    {
        return $this->assetRepository->findBySearch(
            minSqm: $searchForm->get('minSqm')
                ->getData(),
            maxSqm: $searchForm->get('maxSqm')
                ->getData(),
            minPrice: $searchForm->get('minPrice')
                ->getData(),
            maxPrice: $searchForm->get('maxPrice')
                ->getData(),
            type: $searchForm->get('type')
                ->getData(),
            term: $searchForm->get('term')
                ->getData(),
            userType: $searchForm->get('userType')
                ->getData(),
            title: $searchForm->get('title')
                ->getData(),
            floor: $searchForm->get('floor')
                ->getData(),
            agencyFee: $searchForm->get('agencyFee')
                ->getData(),
            address: $searchForm->get('address')
                ->getData()
        );
    }
}
