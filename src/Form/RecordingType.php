<?php

namespace App\Form;

use App\Entity\Record;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('time', DateTimeType::class, [
                'date_label' => 'Starts On',
                'hours' => [8,9,10,11,12,13,14,15,16,17,18,19],
                'minutes' => [0,30],
                'years' => [2023],
            ])
            ->add('comment', null, [
                'attr' => ['rows' => 5],
                'help' => 'help.record_comment',
                'label' => 'recording.label_comment',
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
        ]);
    }
}
