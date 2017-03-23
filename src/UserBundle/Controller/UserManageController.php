<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/users")
 */
class UserManageController extends Controller
{
    /**
     * @Route("/add", name="add_user")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['password_required' => true]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'user_saved');

            return $this->redirectToRoute('add_user');
        }

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('UserBundle:UserManage:manage.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove_user")
     * @Method({"DELETE"})
     * @Security("has_role('ROLE_ROOT')")
     *
     * @param User $user
     *
     * @return Response
     */
    public function removeAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('info', 'User removed');

        return $this->redirectToRoute('add_user');
    }

    /**
     * @Route("/edit/{id}", name="edit_user")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ROOT')")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(User $user, Request $request)
    {
        $originalPassword = $user->getPassword();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $plainPassword = $form->get('plainPassword')->getData();
            if (!empty($plainPassword))  {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $this->get('session')->getFlashBag()->add('info', 'password_changed');
            } else {
                $user->setPassword($originalPassword);
            }

            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'user_saved');

            return $this->redirectToRoute('add_user');
        }

        return $this->render('UserBundle:UserManage:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
