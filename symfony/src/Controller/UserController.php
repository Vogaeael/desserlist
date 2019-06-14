<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function index()
    {
        return $this->render(
            'user/index.html.twig',
            [
                'controller_name' => 'UserController',
            ]
        );
    }

    public function showUser($id)
    {
        $this->isGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCurrentUser($id)) {
            $user = $this->getUserById($id);

            return $this->render(
                'user/view.html.twig',
                [
                    'user' => $user,
                ]
            );
        }

        // @TODO add message to session
        $message = $this->getNotFoundMessage(User::class, $id);

        return $this->redirectToRoute('home');
    }

    //    public function showAllUser()
    //    {
    //        $users = $this->getDoctrine()
    //            ->getRepository(User::class)
    //            ->findAll();
    //
    //        return $this->render(
    //            'user/viewAll.html.twig',
    //            [
    //                'users' => $users,
    //            ]
    //        );
    //    }

    public function deleteUser($id)
    {
        $this->isGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCurrentUser($id)) {
            $user = $this->getUserById($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_show_all');
        }

        // @TODO add message to session
        $message = $this->getNotFoundMessage(User::class, $id);

        return $this->redirectToRoute('home');
    }

    public function editUser($id, Request $request)
    {
        $this->isGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCurrentUser($id)) {
            $user = $this->getUserById($id);

            return $this->handleForm($user, $request);
        }

        // @TODO add message to session
        $message = $this->getNotFoundMessage(User::class, $id);

        return $this->redirectToRoute('home');
    }

    private function handleForm(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    private function getUserById($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        return $user;
    }

    /**
     * Check if the id is of the current user
     *
     * @param $userId
     *
     * @return bool
     */
    private function isCurrentUser($userId)
    {
        $currentUserId = $this->getUser()->getId();

        return $currentUserId === $userId;
    }

    /**
     * @param string $className
     * @param        $id
     *
     * @return string
     */
    private function getNotFoundMessage(string $className, $id): string
    {
        return sprintf('%s with id: %s not found', $className, $id);
    }
}
