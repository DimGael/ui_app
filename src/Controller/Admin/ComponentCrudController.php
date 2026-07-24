<?php

namespace App\Controller\Admin;

use App\Entity\Component;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ComponentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Component::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->overrideTemplate('crud/edit', 'admin/component/edit.html.twig')
            ;
    }

    public function configureFields(string $pageName) : iterable
    {
        $fields = parent::configureFields($pageName);

        foreach ($fields as $i => $field) {
            if (!$field instanceof Field) {
                continue;
            }

            if ($field->getAsDto()->getProperty() == 'htmlcode') {
                $fields[$i] = TextareaField::new('htmlcode')->addCssClass('formHtmlCode');
                break;
            }
        }

        return $fields;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
