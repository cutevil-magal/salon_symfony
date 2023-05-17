<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Record;
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\User;
use App\Form\PostType;
use App\Form\RecordManageType;
use App\Form\ServiceCategoryType;
use App\Form\ServiceType;
use App\Repository\PostRepository;
use App\Repository\RecordRepository;
use App\Repository\ServiceCategoryRepository;
use App\Repository\ServiceRepository;
use App\Security\PostVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/records')]
#[IsGranted('ROLE_ADMIN')]
class RecordController extends AbstractController
{
    /**
     * Lists all entities.
     */
    #[Route('/', name: 'admin_record_index', methods: ['GET'])]
    public function index(
        #[CurrentUser] User $user,
        RecordRepository $recordRepository,
    ): Response {
        $items = $recordRepository->findAll();

        return $this->render('admin/record/index.html.twig', ['items' => $items]);
    }

    /**
     * Displays a form to edit an existing record entity.
     */
    #[Route('/{id<\d+>}/edit', name: 'admin_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Record $record, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecordManageType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'record.updated_successfully');

            return $this->redirectToRoute('admin_record_edit', ['id' => $record->getId()]);
        }

        return $this->render('admin/record/edit.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

}
