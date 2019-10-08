<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class)
            ->add('password', RepeatedType::class, [
            	'type' => PasswordType::class,
            	'invalid_message' => 'Vos mots de passe ne sont pas identiques.',
            	'first_options' => ['label' => 'Mot de passe'],
            	'second_options' => ['label' => 'Confirmez votre de passe'],
			])
            ->add('username', TextType::class, [
            	'label' => 'Nom d\'utilisateur'
			])
            ->add('picture', UrlType::class, [
            	'label' => 'Photo de profil'
			])
			->add('save', SubmitType::class, [
				'label' => 'Valider',
				'attr' => ['class' => 'btn btn-primary']
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
