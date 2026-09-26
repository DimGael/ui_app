<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserCrudController extends AbstractCrudController
{
    private const ROLES = [
        'Admin' => 'ROLE_ADMIN',
        'Utilisateur' => 'ROLE_USER',
        'Super Admin' => 'ROLE_SUPER_ADMIN',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('password')->setPermission('ROLE_SUPER_ADMIN'),
            ChoiceField::new('roles')
                ->setChoices(self::ROLES)
                ->allowMultipleChoices()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $grantAdminAction = Action::new('grantAdminAction', 'Promote Admin')
                ->linkToCrudAction('grantAdmin')
                ->displayIf(static fn(User $user) => !$user->isAdmin());

            $actions->add(Crud::PAGE_DETAIL, $grantAdminAction);
        }

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $impersonate = Action::new('impersonate', 'Impersonate')
                ->linkToUrl(function (User $user) {
                    return $this->generateUrl('app_main', ['_switch_user' => $user->getEmail()]);
                })
                // Cannot impersonate if this user is a super admin
                ->displayIf(static fn(User $user): bool => !in_array('ROLE_SUPER_ADMIN', $user->getRoles()));

            $revokeAdmin = Action::new('revokeAdminAction', 'Revoke Admin')
                ->linkToCrudAction('revokeAdmin')
                ->displayIf(static fn(User $user) => $user->isAdmin());;

            $actions
                ->add(Crud::PAGE_EDIT, $impersonate)
                ->add(Crud::PAGE_DETAIL, $revokeAdmin)
                ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ;
        }

        return $actions
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    #[AdminRoute('/{id}/revoke-admin')]
    public function revokeAdmin(User $user): RedirectResponse
    {
        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw new \RuntimeException('Cannot edit a super admin user');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new \RuntimeException('Cannot revoke admin role, user is not an admin');
        }

        $user->setRoles(array_diff($user->getRoles(), ['ROLE_ADMIN']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_edit', ['entityId' => $user->getId()]);
    }

    #[AdminRoute('/{id}/grant-admin')]
    public function grantAdmin(User $user): RedirectResponse
    {
        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw new \RuntimeException('Cannot edit a super admin user');
        }
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new \RuntimeException('Cannot grant admin role, user is already an admin');
        }

        $user->setRoles(array_merge($user->getRoles(), ['ROLE_ADMIN']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_edit', ['entityId' => $user->getId()]);
    }
}
