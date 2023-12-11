<?php

namespace App\Controller\Admin;

use App\Entity\Reply;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReplyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reply::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('post'),
            AssociationField::new('author'),
            TextField::new('text'),
            AssociationField::new('author')
        ];    }

}
