<?php

namespace MinimalOriginal\CoreBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use MinimalOriginal\CoreBundle\Entity\App;
use MinimalOriginal\CoreBundle\Entity\Routing;

class AppType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->addEventListener(FormEvents::PRE_SET_DATA, function ($event) {
        $form = $event->getForm();
        $entity = $event->getData();

        $routings = $this->em->getRepository(Routing::class)->findAll();
        $routing_choices = array();
        foreach($routings as $routing){
          $routing_choices[$routing->__toString()] = $routing->getId();
        }

        switch($entity->getAttr()){
          case 'homePage':
            $form->add('value',ChoiceType::class,array(
              'choices' => $routing_choices,
              'required' => false,
              'empty_data' => null,
            ));
            break;

          default:
          $form->add('value');
        }

      });

      $builder
        ->add('name')
        ->add('attr')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => App::class,
        ));
    }
}
