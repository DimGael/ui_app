<?php

namespace App\Controller\Admin;

use App\Entity\Component;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

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

        $fields[] = AssociationField::new('category');
        $fields[] = AssociationField::new('style');

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_EDIT, Action::new('preview', 'Preview')
            ->linkToRoute('admin_preview', function (Component $component) {
                return ['htmlcode' => $component->getHtmlcode()];
            }))
            ;
    }
}
