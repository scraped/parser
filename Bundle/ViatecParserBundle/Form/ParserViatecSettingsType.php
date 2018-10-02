<?php

namespace Onixcat\Bundle\ViatecParserBundle\Form;

use Onixcat\Bundle\ViatecParserBundle\Entity\ParserViatecSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParserViatecSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('viatecLogin', TextType::class,
                [
                    'label' => 'onixcat.ui.viatec_form_login',
                    'required' => false
                ]
            )
            ->add('viatecPassword', TextType::class,
                [
                    'label' => 'onixcat.ui.viatec_form_password',
                    'required' => false
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParserViatecSettings::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'parser_viatec_settings';
    }
}
