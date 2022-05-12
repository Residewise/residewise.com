<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Image;
use App\Form\AssetType;
use App\Repository\AssetRepository;
use App\Repository\ImageRepository;
use App\Serializer\AssetNormalizer;
use App\Service\Base64FileUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;

#[Route('/{_locale}/')]
class AssetController extends AbstractController
{
    public function __construct(
        private AssetRepository $assetRepository,
        private Base64FileUtil $base64FileUtil,
        private ImageRepository $imageRepository,
        private Environment $twig,
    ) {
    }

    #[Route(path: '', name: 'app_asset_index', methods: ['GET'])]
    public function index(Request $request, AssetRepository $assetRepository): Response
    {
        $type = $request->get('type');
        $term = $request->get('term');
        $minSqm = (int)$request->get('min-sqm');
        $maxSqm = (int)$request->get('max-sqm');
        $minPrice = (int)$request->get('min-price');
        $maxPrice = (int)$request->get('max-price');
        $assets = $this->assetRepository->findBySearch($minSqm, $maxSqm, $minPrice, $maxPrice, $type, $term);

        $objectNormalizer = new ObjectNormalizer();
        $serializer = new Serializer([
            new AssetNormalizer($objectNormalizer, $this->twig),
        ], [
            new JsonEncoder()
        ]);

        $features = $serializer->serialize(
            [
                'type' => 'FeatureCollection',
                'features' => $assets
            ],
            'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', 'createdAt', 'images', 'owner', 'reviews']]);

        return $this->render('asset/index.html.twig', [
            'assets' => $assets,
            'features' => $features
        ]);
    }

    #[Route('new', name: 'app_asset_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssetRepository $assetRepository): Response
    {
        $asset = new Asset();
        $asset->setOwner($this->getUser());
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('images')->getData();

            foreach ($files as $file) {


                $fileContentBase64 = $this->base64FileUtil->encodeBase64($file->getContent());
                $image = new Image($fileContentBase64, $asset);
                $this->imageRepository->add($image);
            }

            $assetRepository->add($asset);
            return $this->redirectToRoute('user_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asset/new.html.twig', [
            'asset' => $asset,
            'form' => $form,
        ]);
    }

    #[Route('images/{id}', name: 'asset_images', methods: ['GET'])]
    public function images(Asset $asset): Response
    {
        return $this->render('asset/images/show.html.twig', [
            'asset' => $asset
        ]);
    }

    #[Route('show/{id}', name: 'app_asset_show', methods: ['GET'])]
    public function show(Asset $asset): Response
    {
        return $this->render('asset/show.html.twig', [
            'asset' => $asset
        ]);
    }

    #[Route('edit/{id}', name: 'app_asset_edit', methods: ['GET', 'POST'])]
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

    #[Route('delete/{id}', name: 'app_asset_delete', methods: ['POST'])]
    public function delete(Request $request, Asset $asset, AssetRepository $assetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $asset->getId(), $request->request->get('_token'))) {
            $assetRepository->remove($asset);
        }

        return $this->redirectToRoute('app_asset_index', [], Response::HTTP_SEE_OTHER);
    }
}
