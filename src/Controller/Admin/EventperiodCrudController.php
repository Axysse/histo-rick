<?php
namespace App\Controller\Admin;

use App\Entity\EventPeriod;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
;

class EventperiodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventPeriod::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }

}
