<?php
namespace App\Controller\Admin;

use App\Entity\PoliticalEntity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
;

class PoliticalEntityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PoliticalEntity::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('color'),
        ];
    }

}
