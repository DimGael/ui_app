<?php

namespace App\Controller;

use App\Entity\Component;
use App\Form\ComponentType;
use App\Repository\ComponentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/component')]
final class ComponentController extends AbstractController
{
    #[Route(name: 'app_component_index', methods: ['GET'])]
    public function index(ComponentRepository $componentRepository): Response
    {
        return $this->render('component/index.html.twig', [
            'components' => $componentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_component_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $component = new Component();
        $form = $this->createForm(ComponentType::class, $component);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($component);
            $entityManager->flush();

            return $this->redirectToRoute('app_component_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('component/new.html.twig', [
            'component' => $component,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_component_show', methods: ['GET'])]
    public function show(Component $component): Response
    {
        return $this->render('component/show.html.twig', [
            'component' => $component,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_component_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Component $component, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComponentType::class, $component);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_component_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('component/edit.html.twig', [
            'component' => $component,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_component_delete', methods: ['POST'])]
    public function delete(Request $request, Component $component, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$component->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($component);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_component_index', [], Response::HTTP_SEE_OTHER);
    }
}
