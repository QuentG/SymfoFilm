<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\People;
use App\Repository\CategoryRepository;
use App\Repository\PeopleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$years = range(1980, date('Y'));

        $builder
            ->add('title', null, [
            	'label' => 'Titre'
			])
            ->add('image', UrlType::class, [
            	'label' => 'Lien de l\'image'
			])
            ->add('releasedAt', DateType::class, [
            	'label' => 'Sortie en salle',
				'placeholder' => ['year' => '--', 'month' => '--', 'day' => '--'],
				'format' => 'yyyy/MM/dd',
				'years' => $years
			])
            ->add('synopsis', TextareaType::class)
            ->add('categories', EntityType::class, [
            	'class' => Category::class,
				'multiple' => true,
				'query_builder' => function (CategoryRepository $categoryRepository) {
            		return $categoryRepository->createQueryBuilder('c')
						->orderBy('c.title', 'ASC');
				}
			])
            ->add('actors', EntityType::class, [
            	'class' => People::class,
				'label' => 'Acteurs',
				'multiple' => true,
				'query_builder' => function (PeopleRepository $peopleRepository) {
            		return $peopleRepository->createQueryBuilder('p')
						->orderBy('p.lastName', 'ASC');
				}
			])
			->add('save', SubmitType::class, [
				'label' => 'Sauvegarder',
				'attr' => [
					'class' => 'btn btn-primary'
				]
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
