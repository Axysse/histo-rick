<?php
namespace App\Controller\Admin;

use App\Entity\EventTheme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
;

class EventThemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventTheme::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }

}
