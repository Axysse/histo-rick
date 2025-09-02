<?php

namespace App\Controller\Admin;

use App\Entity\Events;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Bundle\SecurityBundle\Security;

class EventsCrudController extends AbstractCrudController
{
        private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Events::class;
    }


    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_INDEX === $pageName) {
            return [
            TextField::new('title'),
            IntegerField::new('year'),
            AssociationField::new('zone'),
                TextField::new('themeNames')
                    ->setLabel('Thèmes'),
                       AssociationField::new('event_type'),
            AssociationField::new('event_period'),
            TextField::new('short_text'),
            TextEditorField::new('event_text'),
            ImageField::new('event_picture')
            ->setUploadDir('public/upload')
            ->setBasePath('/upload')
            ->setFormType(FileUploadType::class)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
            TextField::new('picture_desc'),
            NumberField::new('longitude'),
            NumberField::new('latitude'),
            TextField::new('link'),
            AssociationField::new('author')
            ->hideOnForm(),
            ];
        }
        return [
            TextField::new('title'),
            IntegerField::new('year'),
            AssociationField::new('zone'),
            AssociationField::new('theme')
            ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('event_type'),
            AssociationField::new('event_period'),
            TextField::new('short_text'),
            TextEditorField::new('event_text'),
            ImageField::new('event_picture')
            ->setUploadDir('public/upload')
            ->setBasePath('/upload')
            ->setFormType(FileUploadType::class)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
            TextField::new('picture_desc'),
            NumberField::new('longitude'),
            NumberField::new('latitude'),
            TextField::new('link'),
            AssociationField::new('author')
            ->hideOnForm(),
        ];
    }

     public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Events $event */
        $event = $entityInstance;
        $user = $this->security->getUser();

        if ($user) {
            $event->setAuthor($user);
        }
        parent::persistEntity($entityManager, $event);
    }
}
