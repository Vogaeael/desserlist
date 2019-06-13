<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workdayRepo = $this->getOption($options, 'workdayRepo');
        $userId = $this->getOption($options, 'userId');

        // @TODO verbessern damit nur die noch nicht ausgewählten geholt werden
        $possibleWorkDays = $workdayRepo->findAll();

        $builder
            ->add('workday', ChoiceType::class, ['choices' => $possibleWorkDays])
            ->add('note', TextType::class, ['required' => false])
            ->add('save', SubmitType::class);
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
    private function getOption(array $options, string $key, $default = null)
    {
        $result = $default;
        if (array_key_exists($key, $options)) {
            $result = $options[$key];
        }

        return $result;
    }
}
