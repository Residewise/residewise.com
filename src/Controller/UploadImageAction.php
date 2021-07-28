<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Filter\Validator\ValidatorInterface;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UploadImageAction
{
    private $formFactory;

    private $entityManager;

    private $validator;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(Request $request)
    {
        $media = new Media();
        $form = $this->formFactory->create('', $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($media);
            $this->entityManager->flush();

            $media->setFile(null);

            return $media;
        }

        throw new ValidationException($this->validator->validate($media));
    }
}
