<?php

namespace App\Controller;

use App\Entity\User;

class UserController extends BaseController
{
    public function index()
    {
        return $this->redirectToRoute('home');
    }

    public function showUser($id)
    {
        try {
            $this->isCurrentUser($id);

            $user = $this->getUserById($id);

            return $this->render(
                'user/view.html.twig',
                [
                    'user'     => $user,
                    'messages' => $this->popMessagesFromSession(
                    ),
                ]
            );
        } catch (\Exception $e) {
            $this->addMessageToSession($e->getMessage());

            return $this->redirectToRoute('home');
        }
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

    //    public function deleteUser($id)
    //    {
    //        if ($this->isCurrentUser($id)) {
    //            $user = $this->getUserById($id);
    //
    //            $entityManager = $this->getDoctrine()->getManager();
    //            $entityManager->remove($user);
    //            $entityManager->flush();
    //
    //            return $this->redirect('/logout');
    //        }
    //
    //        // @TODO add message to session
    //        $message = $this->getNotFoundMessage(User::class, $id);
    //
    //        return $this->redirectToRoute('home');
    //    }

    //    public function editUser($id, Request $request)
    //    {
    //        if ($this->isCurrentUser($id)) {
    //            $user = $this->getUserById($id);
    //
    //            return $this->handleForm($user, $request);
    //        }
    //
    //        // @TODO add message to session
    //        $message = $this->getNotFoundMessage(User::class, $id);
    //
    //        return $this->redirectToRoute('home');
    //    }

    //    private function handleForm(User $user, Request $request)
    //    {
    //        $form = $this->createForm(UserType::class, $user);
    //        $form->handleRequest($request);
    //
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $user = $form->getData();
    //
    //            $entityManager = $this->getDoctrine()->getManager();
    //            $entityManager->persist($user);
    //            $entityManager->flush();
    //
    //            return $this->redirectToRoute(
    //                'user_show',
    //                ['id' => $user->getId()]
    //            );
    //        }
    //
    //        return $this->render(
    //            'user/edit.html.twig',
    //            [
    //                'form' => $form->createView(),
    //            ]
    //        );
    //    }

    private function getUserById($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                $this->getNotFoundMessage(User::class, $id)
            );
        }

        return $user;
    }
}
