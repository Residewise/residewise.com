<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageFormType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/{_locale}/im')]
class ChatController extends AbstractController
{
    public function __construct(
        private MessageRepository $messageRepository,
        private ConversationRepository $conversationRepository,
        private HubInterface $hub,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(path: '', name: 'conversation_list')]
    public function conversations(Request $request): Response
    {
        $conversations = $this->conversationRepository->findByUser($this->getUser());

        return $this->render('conversation/list.html.twig', [
            'conversations' => $conversations
        ]);
    }

    #[Route(path: '/load/{id}', name: 'load_conversation')]
    public function load_conversation(Conversation $conversation, Request $request)
    {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setOwner($this->getUser());
        $messageForm = $this->createForm(MessageFormType::class, $message, [
            'action' => $this->urlGenerator->generate('load_conversation', ['id' => $conversation->getId()])
        ]);
        $emptyForm = clone $messageForm;
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $this->messageRepository->add($message);

            $this->hub->publish(
                new Update(
                    'chat', $this->renderView('/conversation/message/message.stream.html.twig',
                    ['message' => $message, 'conversation' => $conversation])
                )
            );
            $messageForm = $emptyForm;
        }


        return $this->render('conversation/index.html.twig', [
            'conversation' => $conversation,
            'messageForm' => $messageForm->createView()
        ]);
    }

    #[Route(path: '/{id}', name: 'conversation')]
    public function chat(Conversation $conversation, Request $request): Response
    {
        return $this->render('conversation/chat.html.twig', [
            'conversation' => $conversation,
        ]);
    }




}
