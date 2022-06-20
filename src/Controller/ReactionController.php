<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Reaction;
use App\Factory\ReactionFactory;
use App\Repository\ReactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;
use function json_decode;

#[Route('/reaction')]
class ReactionController extends AbstractController
{
    public function __construct(
        private readonly ReactionFactory $reactionFactory,
        private readonly ReactionRepository $reactionRepository,
        private readonly HubInterface $hub,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/asset/{id}/react/new', name: 'asset_reaction_create', methods: ['POST'])]
    public function create(Asset $asset, Request $request): Response
    {
        $reaction = $this->assetAlreadyHasUserReaction($asset);
        $content = $request->getContent();
        $jsonContent = json_decode((string)$content, null, 512, JSON_THROW_ON_ERROR);
        $type = $jsonContent->type;

        if ($reaction) {
            if ($reaction->getType() === $type) {
                $this->reactionRepository->remove($reaction, true);
            } else {
                $this->assetSwitchUserReaction($reaction);
            }
        } else {
            $reaction = $this->reactionFactory->create($type, $this->getUser(), $asset);
            $this->entityManager->persist($reaction);
            $this->entityManager->flush();
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $this->hub->publish(
            new Update(
                'reaction', $this->renderView('/reaction/react.stream.html.twig', ['asset' => $asset])
            )
        );

        return $this->json('', 201);

    }

    private function assetSwitchUserReaction(Reaction $reaction): void
    {
        $switchType = $reaction->getType() == 'like' ? 'dislike' : 'like';
        $reaction->setType($switchType);
        $this->reactionRepository->add($reaction, true);
    }

    private function assetAlreadyHasUserReaction(Asset $asset)
    {
        return $this->reactionRepository->findOneBy([
            'owner' => $this->getUser(),
            'asset' => $asset
        ]);
    }

}
