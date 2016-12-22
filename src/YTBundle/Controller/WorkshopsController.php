<?php
// src/YTBundle/Controller/WorkshopsController.php
namespace YTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WorkshopsController extends Controller
{
    public function workshopsListAction($doorl)
    {
        // make a database call
        // to get the $workshops
        $repository = $this->getDoctrine()->getRepository('YTBundle:Workshop');
        //$workshops = $repository->findByContinu($doorl);
        $workshops = $repository->findBy(array('continu'=>$doorl),array('id'=>'ASC'));

        return $this->render(
            'YTBundle:Workshops:workshops_list.html.twig',
            array('workshops' => $workshops)
        );
    }
}