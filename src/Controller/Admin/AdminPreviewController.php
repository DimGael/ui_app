<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminPreviewController extends AbstractController
{
    #[Route(path: '/preview', name: 'admin_preview')]
    public function previewAction(?string $htmlcode): Response
    {
        return $this->render('admin/component/preview.html.twig', ['htmlcode' => $htmlcode]);
    }
}
