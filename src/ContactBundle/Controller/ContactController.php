<?php
namespace ContactBundle\Controller;

use ContactBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller {

	public function newAction (Request $request) {
	
		$contact = new Contact();
		
		$form = $this->createFormBuilder($contact)
        ->add('firstName', 'text', array('label'=>'First Name'))
        ->add('lastName', 'text', array('label'=>'Last Name'))
        ->add('street', 'text', array('label'=>'Street'))
        ->add('houseNumber', 'number', array('label'=>'House Number'))
        ->add('zip', 'number', array('label'=>'Zipcode'))
        ->add('city', 'text', array('label'=>'City'))
        ->add('email', 'email', array('label'=>'E-mail'))
        ->add('message', 'textarea', array('label'=>'Message'))
        ->add('save', 'submit', array('label' => 'Send'))
        ->getForm();

		$form->handleRequest($request);
		
		if ($form->isValid()) {
        // save the contact to the database
    		$em = $this->getDoctrine()->getManager();
   			$em->persist($contact);
    		$em->flush();
    	// redirect to success page
        return $this->redirectToRoute('contact_success');
    	}

        return $this->render('ContactBundle:Contact:new.html.twig', array(
            'form' => $form->createView(),
        ));
	}
	
	public function successAction(){
		return new Response ("Thank you for your submission!");
	}
}
