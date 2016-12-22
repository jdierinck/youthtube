<?php

namespace YTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use YTBundle\Entity\Workshop;
use YTBundle\Entity\WorkshopTranslation;
use YTBundle\Entity\Algemeen;
use YTBundle\Entity\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YTBundle\Form\Type\WorkshopType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction()
    {
    	$algemeen = $this->getDoctrine()
        	->getRepository('YTBundle:Algemeen')
        	->find(1); 
        $options = $this->getDoctrine()->getRepository('YTBundle:Options')->find(1);
        if ($options) {
        	$maintenanceMode = $options->getMaintenanceMode();
        }
        else { $maintenanceMode = 0; }
        return $this->render('YTBundle:Default:index.html.twig', array('algemeen'=>$algemeen,'maintenance_mode'=>$maintenanceMode));
    }
    
    /**
     * @Route("{_locale}/home", requirements={"_locale":"nl|fr"}, name="home")
     */
    public function homeAction()
    {	
        return $this->render('YTBundle:Default:home.html.twig');
    }
    
    /**
     * @Route("{_locale}/workshops/{id}", requirements={"_locale":"nl|fr", "id":"\d+"}, name="workshops")
     */
    public function showAction($id)
    {
    	$repository = $this->getDoctrine()->getRepository('YTBundle:Workshop');
    	$workshop = $repository->find($id);
		if ($workshop) {
			$doorl = $workshop->getContinu();
			$ids = $repository->findAllIds($doorl);
			for ($i=0;$i<count($ids)-1;$i++) { 
				if ($ids[$i]['id'] == $id) { 
					$nextId=$ids[$i+1]['id']; 
					break; 
				} 
				else { 
					$nextId = null; 
				} 
			}
			for ($i=1;$i<count($ids);$i++) { 
				if ($ids[$i]['id'] == $id) { 
					$previousId=$ids[$i-1]['id']; 
					break;
				} 
				else { 
					$previousId = null; 
				} 
			}
    	}
    	return $this->render('YTBundle:Default:show.html.twig', array('workshop'=>$workshop,'nextId'=>!empty($nextId)?$nextId:null,'previousId'=>!empty($previousId)?$previousId:null));
    }
    
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request) {
    	$em = $this->getDoctrine()->getManager();
    	$options = $em->getRepository('YTBundle:Options')->find(1);
    	if (!$options) {
    		$options = new Options();
    		$options->setId(1);
    		$options->setMaintenanceMode(false);
    		$em->persist($options);
    		$em->flush();
    	}
    	$form = $this->createFormBuilder($options)
    		->add('maintenance_mode', 'checkbox', array('label'=>'Zet site in onderhoudsmodus'))
    		->add('submit', 'submit', array('label'=>'Bewaar'))
    		->add('cancel','submit',array('label'=>'Annuleer'))
    		->getForm();
    	$form->handleRequest($request);
    	if ($form->get('cancel')->isClicked()){
    		return $this->redirectToRoute('home');
    	}
    	if ($form->isValid()) {
    		$em->persist($options);
    		$em->flush();
    		return $this->redirectToRoute('indexpage');    
    	}
    	return $this->render('YTBundle:Form:admin.html.twig', array('form'=>$form->createView()));
    }
    
    
    /**
     * @Route("/workshops/new", name="new_workshop")
     */
    public function newWorkshopAction(Request $request){
    	$workshop = new Workshop();
		// Create form using FormBuilder
		//     	$form = $this->createFormBuilder($workshop)
		//     		->add('title','text', array('label'=>'Titel', 'attr'=>array('size'=>50)))
		//     		->add('subtitle','text', array('label'=>'Ondertitel', 'attr'=>array('size'=>50)))
		//     		->add('description','textarea', array('label'=>'Beschrijving', 'attr'=>array('cols'=>50,'rows'=>10)))
		//     		->add('image','text', array('label'=>'Afbeelding', 'attr'=>array('size'=>50), 'empty_data'=>'images/shapeimage_1.png'))
		//     		->add('continu','checkbox', array('label'=>'Doorlopend?'))
		//     		->add('save','submit',array('label'=>'Voeg toe'))
		//     		->getForm();
		// Better way: create class using form class
    	$form = $this->createForm(new WorkshopType(), $workshop);
    	$form->handleRequest($request);
    	if($form->get('cancel')->isClicked()) {
    		return $this->redirectToRoute('home');
    	}
    	elseif ($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$workshop->upload();
    		$em->persist($workshop);
    		$em->flush();
    		$id = $workshop->getId();
			if ($form->get('save_and_translate')->isClicked()) {
				// return $this->redirect($this->generateUrl('edit_workshop',array('_locale'=>'fr','id'=>$id)));
				// alternatively (shorter):
				return $this->redirectToRoute('edit_workshop',array('_locale'=>'fr','id'=>$id));
			}
			else { return $this->redirectToRoute('home'); }
    	}
    	return $this->render('YTBundle:Form:new_workshop.html.twig',array('form'=>$form->createView(),'type'=>'new'));
    	
    }
    
    /**
     * @Route("{_locale}/workshops/edit/{id}", requirements={"_locale":"nl|fr"}, name="edit_workshop")
     */
    public function editWorkshopAction($id, Request $request) {
    	 $em = $this->getDoctrine()->getManager();
    	 $workshop = $em->getRepository('YTBundle:Workshop')->find($id);
    	if (!$workshop) {
        	throw $this->createNotFoundException(
            'No workshop found for id '.$id
        	);
    	}
    	$form = $this->createForm(new WorkshopType(), $workshop);
		$form->handleRequest($request);
		if($form->get('cancel')->isClicked()) {
    		return $this->redirectToRoute('home');
    	}
		elseif ($form->isValid()) {
			$workshop->upload();
			//$em->persist($workshop); not necessary; object is already managed
			$locale = $request->getLocale();
			$workshop->setTranslatableLocale($locale);
			$em->flush();
			return $this->redirectToRoute('home');
		}
		return $this->render('YTBundle:Form:new_workshop.html.twig',array('form'=>$form->createView(),'type'=>'edit','workshop'=>$workshop));
    }

	/**
	 * @Route("{_locale}/workshops/delete/{id}",requirements={"_locale":"nl|fr"}, name="delete_workshop")
	 */
	 public function deleteWorkshopAction($id,Request $request) {
	 	$em = $this->getDoctrine()->getManager();
    	$workshop = $em->getRepository('YTBundle:Workshop')->find($id);
    	if (!$workshop) {
        	throw $this->createNotFoundException(
            'No workshop found for id '.$id
        	);
    	}
	 	$form = $this->createFormBuilder()
	 					->add('save','submit',array('label'=>'Wis'))
	 					->add('cancel','submit',array('label'=>'Annuleer'))
	 					->getForm();
	 	$form->handleRequest($request);
	 	if($form->get('cancel')->isClicked()) {
    		return $this->redirectToRoute('home');
    	}
	 	elseif ($form->isValid()){
				$em->remove($workshop);
				$em->flush();
				return $this->redirectToRoute('home');
		}
		return $this->render('YTBundle:Form:confirm_delete_workshop.html.twig',array('form'=>$form->createView(),'workshop'=>$workshop));
	 }

    /**
     * @Route("{_locale}/algemeen/edit", requirements={"_locale":"nl|fr"}, name="edit_algemeen")
     */
    public function editAlgemeenAction(Request $request){
    	$algemeen = $this->getDoctrine()->getRepository('YTBundle:Algemeen')->find(1);
    	$form = $this->createFormBuilder($algemeen)
    		->add('title','text', array('label'=>'Titel','attr'=>array('size'=>50)))
    		->add('date', 'date', array('label'=>'Datum'))
    		->add('description','ckeditor',array(
    			'config'=>array(
    				'width'=>700,
    				'height'=>500,
    				'toolbar'=>array(
    					array(
    						'name'=>'document',
    						'items'=>array('Source','Preview','Print')
    					),
    					array(
    						'name'=>'clipboard',
    						'items'=>array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo')
    					),
    					array(
    						'name'=>'paragraph',
    						'items'=>array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock')
    					),
    					'/',
    					array(
    						'name'=>'basicstyles',
    						'items'=>array('Bold','Italic','Underline','Strike','Subscript','Superscript')
    					),
    					array(
    						'name'=>'links',
    						'items'=>array('Link','Unlink','Anchor')
    					),
    					array(
    						'name'=>'styles',
    						'items'=>array('Styles','Format','Font','FontSize')
    					),
    					array(
    						'name'=>'colors',
    						'items'=>array('TextColor','BGColor')
    					),    					   					
    				)
    			),
    			'label'=>'Algemene info',
    			'attr'=>array('cols'=>50,'rows'=>30)
    				)
    			)
    		->add('save','submit',array('label'=>'Bewaar'))
    		->add('cancel','submit',array('label'=>'Annuleer'))
    		->getForm();
    	$form->handleRequest($request);
    	if($form->get('cancel')->isClicked()) {
    		return $this->redirectToRoute('home');
    	}
    	elseif ($form->isValid()) {
	    		$em = $this->getDoctrine()->getManager();
    			$em->persist($algemeen);
    			$em->flush();
    			return $this->redirectToRoute('home');
    	}
    	return $this->render('YTBundle:Form:edit_algemeen.html.twig',array('form'=>$form->createView()));
    }
    
    public function showAlgemeenAction(){
    	$algemeen = $this->getDoctrine()->getRepository('YTBundle:Algemeen')->find(1);
    	return new Response ($algemeen->getDescription());
    }
    
    /**
     * @Route("/logout",name="logout")
     */
     public function logoutAction(){
     }
     
     /**
     * @Route("/translatetest", name="test")
     */
     public function testAction() {
     $em = $this->getDoctrine()->getManager();
//      $workshop = new Workshop();
//      $workshop->setTitle('Test vertalingen');
//      $workshop->setSubTitle('Test vertalingen');
//      $workshop->setDescription('Test vertalingen');
//      $workshop->setContinu(0);
     $workshop = $em->getRepository('YTBundle:Workshop')->find(23);
//      $workshop->setTranslatableLocale('nl');
//      $em->refresh($workshop);
     $workshop->setTitle('Test traductions');
     $workshop->setSubTitle('Test traductions');
     $workshop->setDescription('Test traductions');
     $workshop->setTranslatableLocale('en');
     $em->persist($workshop);
     $em->flush();
     var_dump($workshop);
     return new Response('Vertaling ingevoegd');
     
    // $workshop = $em->getRepository('YTBundle:Workshop')->find(23);
//     $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
//     $translations = $repository->findTranslations($workshop);
//     var_dump($workshop);
     }

}
