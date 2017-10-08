<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Province;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class DefaultType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('province', EntityType::class, array(
                'class' => 'AppBundle:Province',
                'placeholder' => '',
                'choice_label' => 'name',
                'label' => '省份',
            ))
            // reserve position(保留占用位置，否则通过事件加的这个字段会排列到最后)
            ->add('city', 'hidden')
        ;

        // 下面的代码来自http://symfony.com/doc/2.8/form/dynamic_form_modification.html的
        // Dynamic Generation for Submitted Forms
        $formModifier = function (FormInterface $form, Province $province = null) {
            $cities = null === $province ? array() : $province->getCities();

            $form->add('city', EntityType::class, array(
                'class' => 'AppBundle:City',
                'placeholder' => '',
                'choices' => $cities,
                'choice_label' => 'name',
                'label' => '城市',
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity
                $data = $event->getData();
                dump($data->getProvince());
                $formModifier($event->getForm(), $data->getProvince());
            }
        );

        // $builder->get('province')->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) use ($formModifier) {
        //         // It's important here to fetch $event->getForm()->getData(), as
        //         // $event->getData() will get you the client data (that is, the ID)
        //         $province = $event->getForm()->getData();

        //         // since we've added the listener to the child, we'll have to pass on
        //         // the parent to the callback functions!
        //         $formModifier($event->getForm()->getParent(), $province);
        //     }
        // );
    }
}

