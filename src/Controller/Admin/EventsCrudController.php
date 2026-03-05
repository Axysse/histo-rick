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
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

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

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addCssFile('https://unpkg.com/leaflet/dist/leaflet.css')
            ->addJsFile('https://unpkg.com/leaflet/dist/leaflet.js')
            ->addJsFile('build/admin-leaflet.js');
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
                TextField::new('event_picture_file', 'Photo de l\'événement')
                    ->setFormType(VichImageType::class)
                    ->onlyOnForms(),
                ImageField::new('event_picture', 'Aperçu')
                    ->setBasePath('%env(S3_BASE_URL)%')
                    ->hideOnForm()
                    ->setRequired(false),
                TextField::new('picture_desc'),
                NumberField::new('latitude'),
                NumberField::new('longitude'),
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

            TextField::new('event_picture_file', 'Photo de l\'événement')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            ImageField::new('event_picture', 'Aperçu')
                ->setBasePath('%env(S3_BASE_URL)%') // L'URL de ton bucket Scaleway
                ->hideOnForm()
                ->setRequired(false),
            TextField::new('picture_desc'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
TextField::new('map')
    ->setLabel('Carte')
    ->onlyOnForms()
    ->setFormTypeOptions([
        'mapped' => false,
    ])
    ->setHelp('<div id="leaflet-map" style="height:400px;"></div>')
    ->renderAsHtml(),
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
