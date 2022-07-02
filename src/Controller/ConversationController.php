<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ConversationFormType;
use App\Form\KeywordFormType;
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
use function exp;
use function explode;

#[Route('/im')]
class ConversationController extends AbstractController
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly ConversationRepository $conversationRepository,
        private readonly HubInterface $hub,
    ) {
    }

    #[Route(path: '/all', name: 'conversations', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function conversations(Request $request): Response
    {
        $form = $this->createForm(KeywordFormType::class);
        $form->handleRequest($request);
        $page = $request->query->getInt('page', 1);

        $pagination = $this->conversationRepository->findByUserAndKeyword(
            user: $this->getUser(),
            keyword: null,
            page: $page
        );

        if ($form->isSubmitted() && $form->isValid()) {

            $keyword = $form->get('keyword')->getData();
            $pagination = $this->conversationRepository->findByUserAndKeyword(
                user: $this->getUser(),
                keyword: $keyword,
                page: $page
            );

            return $this->render('conversation/list.html.twig', [
                'form' => $form->createView(),
                'pagination' => $pagination,
            ]);
        }

        return $this->render('conversation/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    #[Route(path: '/new', name: 'conversation_new')]
    public function new(Request $request): Response
    {
        $conversation = new Conversation();
        $conversationForm = $this->createForm(ConversationFormType::class, $conversation);
        $conversationForm->handleRequest($request);

        if ($conversationForm->isSubmitted() && $conversationForm->isValid()) {
            $users = $conversationForm->get('users')->getData();

//            $findPreviousConversation = $this->conversationRepository->findByUsers($users);
//
//            if($findPreviousConversation){
//                return $this->redirectToRoute('conversation', ['id' => $findPreviousConversation->getId()]);
//            }

            $conversation->addUser($this->getUser());
            $conversation->setTitle($conversation->getUsersFirstNamesAsCommaSeperatedString());
            $this->conversationRepository->add($conversation);


            return $this->redirectToRoute('conversation', ['id' => $conversation->getId()]);
        }

        return $this->render('conversation/new.html.twig', [
            'conversationForm' => $conversationForm->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'conversation')]
    public function chat(Conversation $conversation, Request $request): Response
    {
        $messages = $this->messageRepository->findByConversation($conversation);
        $message = new Message();
        $message->setConversation($conversation);
        $message->setOwner($this->getUser());

        $messageForm = $this->createForm(MessageFormType::class, $message);

        $emptyForm = clone $messageForm;
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $this->messageRepository->add($message);
            $this->hub->publish(
                update: new Update(
                    'chat', $this->renderView(
                    '/conversation/message/message.stream.html.twig',
                    ['conversation' => $conversation, 'messages' => $messages ],
                ), private: true
                )
            );
            $messageForm = $emptyForm;

            return $this->redirectToRoute('conversation', ['id'=> $conversation->getId()]);
        }

        return $this->render('conversation/chat.html.twig', [
            'messages' => $messages,
            'conversation' => $conversation,
            'messageForm' => $messageForm->createView()
        ]);
    }

}
