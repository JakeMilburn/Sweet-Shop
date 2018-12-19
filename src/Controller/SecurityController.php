<?php

namespace App\Controller;

use App\Entity\User;
use http\Env\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
  /**
   * @Route("/login", name="login")
   */
  public function login(Request $request, AuthenticationUtils $utils)
  {
    $error = $utils->getLastAuthenticationError();

    $lastUsername = $utils->getLastUsername();

    return $this->render('security/login.html.twig', [
      'error' => $error,
      'last_username' => $lastUsername
    ]);
  }

  /**
   * @Route("/logout", name="logout")
   */
  public function logout()
  {

  }

  private $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
  {
    $this->encoder = $encoder;
  }

  /**
   * @Route("/register", name="register")
   */
  public function register(Request $request)
  {
    $user = new User();

    $form = $this->createFormBuilder($user)
      ->add('username', TextType::class, array(
        'required' => true,
        'attr' => array('class' => 'form-control')))
      ->add('email', EmailType::class, array(
        'required' => true,
        'attr' => array('class' => 'form-control')))
      ->add('password', PasswordType::class, array(
        'required' => true,
        'attr' => array('class' => 'form-control')))
      ->add('save', SubmitType::class, array(
        'label' => 'Create',
        'attr' => array(
          'class' => 'btn btn-primary mt-3'
        )
      ))
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $form->getData();

      $user->setPassword(
        $this->encoder->encodePassword($user, $user->getPassword())
      );

      $user->setRoles(['ROLE_USER']);

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();

      return $this->redirectToRoute('sweet_list');
    }

    return $this->render('security/register.html.twig', array(
      'form' => $form->createview()
    ));
  }

  /**
   * @Route("/manage", name="manage")
   * @Method({"GET"})
   */
  public function manage(Request $request)
  {
    $users = $this->getDoctrine()->getRepository(User::class)->findAll();

    return $this->render('security/manage.html.twig', array
    ('users' => $users));
  }


  /**
   * @Route("/perms", name="perms")
   * @Method({"GET"})
   */
  public function permissions() {

    $users = $this->getDoctrine()->getRepository(User::class)->findAll();
    $entityManager = $this->getDoctrine()->getManager();

    foreach($users as $user) {
      $id = $user->getId();
      if (array_key_exists($id, $_POST)){
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
      } else {
        $user->setRoles(['ROLE_USER']);
      }

      $entityManager->persist($user);
    }

    $entityManager->flush();

    return $this->redirectToRoute('sweet_list');
  }



}
