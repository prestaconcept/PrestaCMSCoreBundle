<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Page Form
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageType extends AbstractType
{
    public function getName()
    {
        return 'page';
    }
    
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Application\Presta\CMSCoreBundle\Entity\Page',
        );
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //SEO
            ->add('url', null, array('label' => 'admin.url'))
            ->add('title', null, array('label' => 'admin.title'))
            ->add('metaKeywords', null, array('label' => 'admin.metaKeywords'))
            ->add('metaDescription', 'textarea', array('label' => 'admin.metaDescription'))
        //Settings
            ->add('name', null, array('label' => 'admin.name'))
            ->add('active', 'choice', array(
                'choices'   => array(true => 'Yes', false => 'No'),
                'required'  => true,
                'label'     => 'admin.active'
            ))
            ->add('settings', 'sonata_type_immutable_array', array(
                'keys' => array(
                    //here add specific type settings based on PageType
                    //var_dump($builder->getForm()->getData());die;
                    //array('content', 'textarea', array('attr' => array('class'=> 'ckeditor'))),
                )
            ))
        ;
        
    }
}