<?php

namespace App\Form;

use App\Entity\Workday;
use App\Repository\WorkdayRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        /** @var WorkdayRepository $workdayRepo */
        $workdayRepo = $this->getOption(
            $options,
            'workdayRepo'
        );
        $userId = $this->getOption($options, 'userId');

        $possibleWorkdays = $workdayRepo->findUnregisteredWorkdays(
            $userId
        );

        if ([] !== $possibleWorkdays) {
            $builder->add(
                'workday',
                ChoiceType::class,
                [
                    'choices'      => $possibleWorkdays,
                    'choice_label' => $this->getWorkdayLabelFunction(
                    ),
                ]
            );
        }

        $builder->add(
            'note',
            TextType::class,
            [
                'attr'     => ['placeholder' => 'Note'],
                'required' => false,
            ]
        )
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired('userId');
        $resolver->setRequired('workdayRepo');
    }

    /**
     * Get Value from array or the default value
     *
     * @param array  $options
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed|null
     */
    private function getOption(
        array $options,
        string $key,
        $default = null
    ) {
        $result = $default;
        if (array_key_exists($key, $options)) {
            $result = $options[$key];
        }

        return $result;
    }

    /**
     * @return \Closure
     */
    private function getWorkdayLabelFunction()
    {
        return function ($choice, $key, $value) {
            $label = strtoupper($key);
            if (is_a($choice, Workday::class)) {
                $label = $choice->getDate()->format('Y-m-d');
                $label .= ': ' . $choice->getMeal()->getName();
            }

            return $label;
        };
    }
}
