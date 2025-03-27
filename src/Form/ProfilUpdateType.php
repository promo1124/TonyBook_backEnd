<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Ajout du champ email
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            // Ajout du champ prénom
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
            ])
            // Ajout du champ nom
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            // Ajout du champ adresse
            ->add('address', TextareaType::class, [
                'label' => 'Adresse',
                'required' => false,
            ])
            // Ajout du champ code postal
            ->add('cp', NumberType::class, [
                'label' => 'Code Postal',
                'required' => false,
            ])
            // Ajout du champ ville
            ->add('town', TextType::class, [
                'label' => 'Ville',
                'required' => false,
            ])
            // Ajout du champ pays
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => false,
            ])
            // Ajout du champ numéro de téléphone
            ->add('phoneNumber', NumberType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])
            // Ajout du champ mot de passe (requis seulement si on souhaite le changer)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,  // Le mot de passe est optionnel
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'mapped' => false,  // Ne pas lier directement à l'entité User
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
