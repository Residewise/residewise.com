<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\User;
use App\Factory\BookmarkFactory;
use App\Repository\BookmarkRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\Turbo\TurboBundle;

#[Route(path: '/bookmark')]
class BookmarkController extends AbstractController
{
    public function __construct(
        private readonly BookmarkRepository $bookmarkRepository,
        private readonly BookmarkFactory $bookmarkFactory,
        private readonly HubInterface $hub,
    ) {
    }

    #[Route(path: 'asset/{id}/new', name: 'add_bookmark', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Asset $asset, Request $request): Response
    {
        $bookmark = $this->bookmarkRepository->findOneBy([
            'owner' => $this->getUser(),
            'asset' => $asset,
        ]);
        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);

        if ($bookmark === null) {
            $bookmark = $this->bookmarkFactory->create($asset, $currentUser);
            $this->bookmarkRepository->add($bookmark, true);
        } else {
            $this->bookmarkRepository->remove($bookmark, true);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $this->hub->publish(
            new Update(
                'bookmark',
                $this->renderView('/bookmark/bookmark.stream.html.twig', [
                    'asset' => $asset,
                ])
            )
        );

        return $this->json('', 200);

    }
}
