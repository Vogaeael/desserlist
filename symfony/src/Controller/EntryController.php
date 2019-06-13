<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntryController extends AbstractController
{
    /**
     * @Route("/entry", name="entry")
     */
    public function index()
    {
        return $this->render(
            'entry/index.html.twig',
            [
                'controller_name' => 'EntryController',
            ]
        );
    }

    /**
     * @Route("/entry/{id}", name="show_entry")
     */
    public function showEntry($id)
    {
        $entry = $this->getEntryById($id);

        return $this->render(
            'entry/view.html.twig',
            ['entry' => $entry,]
        );
    }

    /**
     * @Route("/entries", name="entrie_show_all")
     */
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
            ->findBy(['userId' => $userId]);

        return $this->render(
            'entry/viewAllByUser.html.twig',
            [
                'entries' => $entries,
            ]
        );
    }

    /**
     * @Route("/entries/{date}", name="entrie_on_date")
     */
    public function showEntriesOnDate()
    {
        // @TODO überarbeiten
        $entries = $this->getDoctrine()
            ->getRepository(Entry::class)
            ->findAll();
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

    /**
     * @return null|User
     */
    private function getCurrentUser(){
        var_dump($this->get('security.context'));
        return $this->get('security.context')->getToken()->getUser();
    }
}
