<?php
namespace YTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller {

	public function menuAction(){
			$repository = $this->getDoctrine()->getRepository('YTBundle:Workshop');
			// using a custom repository class
			$firstworkshop = $repository->findFirstId(0);
			$firstworkshopid = $firstworkshop ? $firstworkshop[0]['id'] : 0 ;
			$firstworkshop_doorl = $repository->findFirstId(1);
			$firstworkshop_doorlid = $firstworkshop_doorl ? $firstworkshop_doorl[0]['id'] : 0 ;

		$navigation = array(
			// 0 => array('href'=>$this->generateUrl('indexpage'),'caption'=>'Welkom'),
			1 => array('href'=>$this->generateUrl('home'),'caption'=>'Home'),
			2 => array('href'=>$this->generateUrl('workshops',array('id'=>$firstworkshopid)),'caption'=>'Workshops'),
			3 => array('href'=>$this->generateUrl('workshops',array('id'=>$firstworkshop_doorlid)),'caption'=>'Doorlopende activiteiten'),
		);
		return $this->render ('YTBundle:Menu:menu.html.twig', array('navigation' => $navigation));
	}

}