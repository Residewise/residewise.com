<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Form\AgencyFormType;
use App\Repository\AgencyRepository;
use App\Service\Base64FileUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/agency')]
class AgencyController extends AbstractController
{

    public function __construct(
        private readonly AgencyRepository $agencyRepository,
        private readonly Base64FileUtil $base64FileUtil,
    ) {
    }

    #[Route(path: '/new', name: 'agency_new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $agency = new Agency();
        $agency->setOwner($this->getUser());
        $form = $this->createForm(AgencyFormType::class, $agency, [
            'action' => $this->generateUrl('agency_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array<UploadedFile> $files */
            $logoFile = $form->get('logo')->getData();
            $fileContentBase64 = $this->base64FileUtil->encodeBase64($logoFile->getContent());
            $agency->setLogo($fileContentBase64);
            $this->agencyRepository->add($form->getData(), true);
            return $this->redirectToRoute('user_agency', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agency/new.html.twig', [
            'agencyForm' => $form->createView()
        ]);
    }

    #[Route(path: '/edit', name: 'agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $agency = $this->getUser()->getAgency();
        $form = $this->createForm(AgencyFormType::class, $agency, [
            'action' => $this->generateUrl('agency_edit')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array<UploadedFile> $files */
            $logoFile = $form->get('logo')->getData();
            $fileContentBase64 = $this->base64FileUtil->encodeBase64($logoFile->getContent());
            $agency->setLogo($fileContentBase64);
            $this->agencyRepository->add($agency, true);
            return $this->redirectToRoute('user_agency', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('agency/new.html.twig', [
            'agencyForm' => $form->createView()
        ]);
    }

}
