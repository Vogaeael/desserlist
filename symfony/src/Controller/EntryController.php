<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\Workday;
use App\Form\EntryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class EntryController extends BaseController
{
    public function index()
    {
        return $this->render(
            'entry/index.html.twig',
            [
                'controller_name' => 'EntryController',
            ]
        );
    }

    public function showEntry($id)
    {
        $entry = $this->getEntryById($id);
        $userId = $entry->getUser()->getId();

        if ($this->isCurrentUser($userId)) {
            return $this->render(
                'entry/view.html.twig',
                [
                    'entry'    => $entry,
                    'messages' => $this->popMessagesFromSession(
                    ),
                ]
            );
        }

        $message = $this->getNotFoundMessage(Entry::class, $id);
        $this->addMessageToSession($message);

        return $this->redirectToRoute('entry_show_all');
    }

    public function showAllEntry()
    {
        $user = $this->getUser();

        $userId = null;
        if ($user) {
            $userId = $user->getId();
        }

        $entries = $this->getDoctrine()
            ->getRepository(Entry::class)
            ->findUserEntriesOrderedByDate($userId);

        $addEntry = !$this->registeredInAllWorkdays($userId);

        $view = $this->render(
            'entry/viewAllByUser.html.twig',
            [
                'entries'  => $entries,
                'addEntry' => $addEntry,
                'messages' => $this->popMessagesFromSession(),
            ]
        );

        return $view;
    }

    public function createEntry(Request $request)
    {
        $entry = new Entry();

        $user = $this->getUser();
        $form = $this->getEntryForm(
            $request,
            $entry,
            $user->getId()
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $entry->setUser($user);

            $this->saveEntry($entry);

            return $this->redirect('/entries');
        }

        return $this->render(
            'entry/create.html.twig',
            [
                'entryForm' => $form->createView(),
                'messages'  => $this->popMessagesFromSession(),
            ]
        );
    }

    public function editEntry(Request $request, $id)
    {
        $entry = $this->getEntryById($id);
        $userId = $entry->getUser()->getId();

        if ($this->isCurrentUser($userId)) {
            $userId = $this->getUser()->getId();
            $form = $this->getEntryForm(
                $request,
                $entry,
                $userId
            );

            if ($form->isSubmitted() && $form->isValid()) {
                $this->saveEntry($entry);

                return $this->redirect('/entries');
            }

            return $this->render(
                'entry/edit.html.twig',
                [
                    'entryForm' => $form->createView(),
                    'messages'  => $this->popMessagesFromSession(
                    ),
                ]
            );
        }

        $message = $this->getNotFoundMessage(Entry::class, $id);
        $this->addMessageToSession($message);

        return $this->redirectToRoute('entry_show_all');
    }

    public function deleteEntry($id)
    {
        $entry = $this->getEntryById($id);
        $userId = $entry->getUser()->getId();

        if ($this->isCurrentUser($userId)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entry_show_all');
    }

    /**
     * @param $id
     *
     * @return Entry|null
     */
    private function getEntryById($id)
    {
        $entry = $this->getDoctrine()
            ->getRepository(Entry::class)
            ->find($id);

        if (!$entry) {
            // @TODO catch it somewhere
            throw $this->createNotFoundException(
                'No entry found for id ' . $id
            );
        }

        return $entry;
    }

    /**
     * Persist and flush the entry
     *
     * @param Entry $entry
     */
    private function saveEntry(Entry $entry): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entry);
        $entityManager->flush();
    }

    /**
     * create entry form and handle the Request
     *
     * @param Request $request
     * @param Entry   $entry
     * @param         $userId
     *
     * @return EntryType
     */
    private function getEntryForm(
        Request $request,
        Entry $entry,
        $userId
    ): FormInterface {
        $workdayRepo = $this->getDoctrine()->getRepository(
            Workday::class
        );
        $form = $this->createForm(
            EntryType::class,
            $entry,
            [
                'userId'      => $userId,
                'workdayRepo' => $workdayRepo,
            ]
        );

        return $form->handleRequest($request);
    }

    /**
     * True if the user is registered on all workdays
     *
     * @param $userId
     *
     * @return bool
     */
    private function registeredInAllWorkdays($userId)
    {
        $notRegisteredWorkdays = $this->getDoctrine()
            ->getRepository(Workday::class)
            ->findUnregisteredWorkdays($userId);

        return [] === $notRegisteredWorkdays;
    }
}
