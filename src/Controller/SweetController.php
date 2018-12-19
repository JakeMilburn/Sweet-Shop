<?php

namespace App\Controller;

use App\Entity\Sweet;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SweetController extends Controller
{

  /**
   * @Route("/", name="sweet_list")
   * @Method({"GET"})
   */
  public function index()
  {

    $sweets = $this->getDoctrine()->getRepository(Sweet::class)->findAll();

    return $this->render('sweet/index.html.twig', array
    ('sweets' => $sweets));
  }

  /**
   * @Route("/checkout", name="checkout")
   * @Method({"POST"})
   */
  public function checkout()
  {

    $sweets = $this->getDoctrine()->getRepository(Sweet::class)->findAll();
    if(empty($sweets)){
      return;
    }
    $price = 0;
    $weight = 0;
    foreach($sweets as $sweet) {
      $quantity = $_POST[$sweet->getId()];
      $weight += $sweet->getWeight() * (float) $quantity;
      $price += $sweet->getPrice() * (float) $quantity;
    }

    if ($weight >= 40 && $weight <= 250) {
      $shipping = 1.50;
    } elseif ($weight > 250 && $weight <= 500 ) {
      $shipping = 2.00;
    } else {
      $shipping = 2.50;
    }

    $totalCost = $price + $shipping;

    return $this->render('sweet/checkout.html.twig', array
    ('price' => $price, 'weight' => $weight, 'shipping' => $shipping, 'total' => $totalCost));
  }


  /**
   * @Route("/sweet/new", name="new_sweet")
   * Method({"GET", "POST"})
   */
  public function new(Request $request)
  {

    $sweet = new Sweet();

    $form = $this->createFormBuilder($sweet)
      ->add('name', TextType::class, array('attr' =>
        array('class' => 'form-control')))
      ->add('price', null, array('attr' =>
      array('class' => 'form-control')))
      ->add('weight', null, array('attr' =>
        array('class' => 'form-control')))
      ->add('save', SubmitType::class, array(
        'label' => 'Add',
        'attr' => array(
          'class' => 'btn btn-primary mt-3'
        )
      ))
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $sweet = $form->getData();

      $entityManager = $this->getDoctrine()->getManager();

      $entityManager->persist($sweet);
      $entityManager->flush();

      return $this->redirectToRoute('sweet_list');
    }

    return $this->render('sweet/new.html.twig', array(
      'form' => $form->createview()
    ));
  }


  /**
   * @Route("/sweet/edit/{id}", name="edit_sweet")
   * Method({"GET", "POST"})
   */
  public function edit(Request $request, $id)
  {
    $sweet = new Sweet();
    $sweet = $this->getDoctrine()->getRepository(Sweet::class)->find($id);
    $form = $this->createFormBuilder($sweet)
      ->add('name', TextType::class, array('attr' =>
        array('class' => 'form-control')))
      ->add('price', null, array('attr' =>
        array('class' => 'form-control')))
      ->add('weight', null, array('attr' =>
        array('class' => 'form-control')))
      ->add('save', SubmitType::class, array(
        'label' => 'Update',
        'attr' => array(
          'class' => 'btn btn-primary mt-3'
        )
      ))
      ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();
      return $this->redirectToRoute('sweet_list');
    }
    return $this->render('sweet/edit.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
   * @Route("/sweet/delete/{id}")
   * @Method({"DELETE"})
   */
  public function delete(Request $request, $id)
  {
    $sweet = $this->getDoctrine()->getRepository(Sweet::class)->find($id);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($sweet);
    $entityManager->flush();
    $response = new Response();
    $response->send();
  }

}
