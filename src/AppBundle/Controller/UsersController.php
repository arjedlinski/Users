<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class UsersController extends Controller
{
    /**
     * @Route("/users", name="Users_list")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:Users')->findAll();
        return $this->render('Users/index.html.twig', array(
            'users' => $users,
        ));
    }
    
    /**
     * @Route("/users/create", name="Users_create")
     */
    public function createAction(Request $request)
    {
        $user = new Users();
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('telephone', TextType::class)
            ->add('address', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Add User'))
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('Users_list');
        }
    
        return $this->render('users/newUser.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/users/delete/{id}", name="Users_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')
                       ->findOneBy(array('id' => $id));
        $em->remove($user);
        $em->flush();                                                     
        return $this->redirectToRoute('Users_list');
    }
    
    /**
     * @Route("/users/edit/{id}", name="Users_edit")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')
                       ->findOneBy(array('id' => $id));
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('telephone', TextType::class)
            ->add('address', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Edit User'))
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('Users_edit', array('id' => $id));
        }
        // replace this example code with whatever you need
        return $this->render('Users/editUser.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
        ));
    }
    
    /**
     * @Route("/users/show/{id}", name="Users_show")
     */
    public function showAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')
                       ->findOneBy(array('id' => $id));
        return $this->render('Users/userProfile.html.twig', array(
            'user' => $user,
            'id' => $id,
        ));
    }
}
