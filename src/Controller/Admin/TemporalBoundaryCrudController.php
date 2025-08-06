<?php

namespace App\Controller\Admin;

use App\Entity\TemporalBoundary;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TemporalBoundaryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TemporalBoundary::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $entity = new $entityFqcn();

        $this->handleGeometryFromRequest($entity);

        return $entity;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleGeometryFromRequest($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);
    }


    private function handleGeometryFromRequest($entity): void
    {
        $request = $this->getContext()->getRequest();
        $formData = $request->request->all();

        if (isset($formData['TemporalBoundary']['geometryString'])) {
            $json = json_decode($formData['TemporalBoundary']['geometryString'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $entity->setGeometry($json);
            }
        }
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('PoliticalEntity'),
            IntegerField::new('start_date'),
            IntegerField::new('end_date'),

            TextareaField::new('geometryString', 'Polygone GeoJSON')
                ->onlyOnForms()
                ->setFormTypeOption('mapped', false)
                ->setFormTypeOption('attr', [
                    'class' => 'geojson-field',
                    'id' => 'geometry-input'
                ])
        ];
    }
}
