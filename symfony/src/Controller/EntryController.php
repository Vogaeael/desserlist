<?php

namespace App\Controller;

use App\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
