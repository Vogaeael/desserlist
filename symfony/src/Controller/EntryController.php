<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\Workday;
use App\Form\EntryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EntryController extends AbstractController
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

        return $this->render(
            'entry/view.html.twig',
            ['entry' => $entry,]
        );
    }

    public function showAllEntry()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $userId = null;
        if ($user) {
            $userId = $user->getId();
        }

        $entries = $this->getDoctrine()
            ->getRepository(Entry::class)
            ->findBy(['user' => $userId]);

        //        var_dump($entries);
        //        die;

        return $this->render(
            'entry/viewAllByUser.html.twig',
            [
                'entries' => $entries,
            ]
        );
    }

    public function showEntriesOnDate($date)
    {
        // @TODO überarbeiten
        $entries = $this->getDoctrine()
            ->getRepository(Entry::class)
            ->findAll();
    }

    public function createEntry(Request $request)
    {
        $entry = new Entry();

        $user = $this->getUser();
        $workdayRepo = $this->getDoctrine()->getRepository(Workday::class);
        $form = $this->createForm(
            EntryType::class,
            $entry,
            [
                'userId'      => $user->getId(),
                'workdayRepo' => $workdayRepo,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entry->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entry);
            $entityManager->flush();

            return $this->redirect('/entries');
        }

        return $this->render(
            'entry/create.html.twig',
            [
                'entryForm' => $form->createView(),
            ]
        );
    }

    public function editEntry(Request $request, $id)
    {
        $entry = $this->getEntryById($id);

        $user = $this->getUser();
        $workdayRepo = $this->getDoctrine()->getRepository(Workday::class);
        $form = $this->createForm(
            EntryType::class,
            $entry,
            [
                'userId'      => $user->getId(),
                'workdayRepo' => $workdayRepo,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entry);
            $entityManager->flush();

            return $this->redirect('/entries');
        }

        return $this->render(
            'entry/edit.html.twig',
            [
                'entryForm' => $form->createView(),
            ]
        );
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
            throw $this->createNotFoundException(
                'No entry found for id ' . $id
            );
        }

        return $entry;
    }
}
