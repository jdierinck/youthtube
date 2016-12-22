<?php

namespace YTBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', array('label'=>'Titel', 'attr'=>array('size'=>50)))
            ->add('subtitle','text', array('label'=>'Ondertitel', 'attr'=>array('size'=>50)))
//             ->add('description','textarea', array('label'=>'Beschrijving', 'attr'=>array('cols'=>50,'rows'=>10)))
            ->add('description','ckeditor',array(
    			'config'=>array(
    				'width'=>700,
    				'height'=>300,
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
    			'label'=>'Beschrijving',
    			'attr'=>array('cols'=>50,'rows'=>10)
    				)
    			)
            ->add('file','file', array('label'=>'Voeg afbeelding toe', 'attr'=>array('size'=>50),'required'=>false,'image_path'=>'webPath'))
            ->add('continu','checkbox', array('label'=>'Doorlopend?','required'=>false))
            ->add('save','submit')
            ->add('save_and_translate','submit', array('label'=>'Bewaar en vertaal'))
            ->add('cancel','submit', array('label'=>'Annuleer'))
        ;
    }

    public function getName()
    {
        return 'workshop';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    $resolver->setDefaults(array(
        'data_class' => 'YTBundle\Entity\Workshop',
        'csrf_protection' => true,
    ));
    }
}