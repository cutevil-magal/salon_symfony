<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Record;
use App\Entity\ServiceCategory;
use App\Form\PostType;
use App\Form\RecordingType;
use App\Repository\ServiceCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', defaults: ['_format' => 'html'])]
    public function index(Request $request, string $_format, ServiceCategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        return $this->render('main/index.'.$_format.'.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/recording/{id}', name: 'recording', defaults: ['_format' => 'html'])]
    public function recording(Request $request, string $_format, ServiceCategory $category, EntityManagerInterface $entityManager) {
        $categories = '$categories';

        /** @var string|null $token */
        $token = $request->request->get('token');

        $record = new Record();
        $record->setDuration(60);
        $record->setService($category);
        $record->setStatus('new');

        $form = $this->createForm(RecordingType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($record);
            $entityManager->flush();
            $this->addFlash('success', 'recording.submitted');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('main/recording.'.$_format.'.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }
}
