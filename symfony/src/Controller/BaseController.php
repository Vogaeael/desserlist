<?php

namespace App\Controller;

use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BaseController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param $message
     */
    protected function addMessageToSession($message)
    {
        $messages = $this->session->get('messages', []);
        $messages[] = $message;
        $this->session->set('messages', $messages);
    }

    /**
     * @return mixed
     */
    protected function getMessagesFromSession()
    {
        return $this->session->get('messages', null);
    }

    /**
     * Pop all messages and remove them from the session
     *
     * @return mixed
     */
    protected function popMessagesFromSession()
    {
        $messages = $this->getMessagesFromSession();
        $this->emptyMessagesFromSession();

        return $messages;
    }

    /**
     * Set the messages in the session to null
     */
    protected function emptyMessagesFromSession()
    {
        $this->session->set('messages', null);
    }

    /**
     * @param string $className
     * @param        $id
     *
     * @return string
     */
    protected function getNotFoundMessage(
        string $className,
        $id
    ): string {
        return sprintf(
            '%s with id: %s not found',
            $className,
            $id
        );
    }

    /**
     * Check if the id is of the current user
     *
     * @param $userId
     *
     * @return bool
     */
    protected function isCurrentUser($userId)
    {
        $currentUserId = $this->getUser()->getId();
        if ($currentUserId !== $userId) {
            throw $this->createAccessDeniedException();
        }
        return $currentUserId === $userId;
    }
}
