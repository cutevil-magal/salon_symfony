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
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\User;
use App\Form\PostType;
use App\Form\ServiceCategoryType;
use App\Form\ServiceType;
use App\Repository\PostRepository;
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

#[Route('/admin/service')]
#[IsGranted('ROLE_ADMIN')]
class ServiceController extends AbstractController
{
    /**
     * Lists all entities.
     */
    #[Route('/', name: 'admin_service_index', methods: ['GET'])]
    public function index(
        #[CurrentUser] User $user,
        ServiceRepository $serviceRepository,
    ): Response {
        $items = $serviceRepository->findAll();

        return $this->render('admin/service/index.html.twig', ['items' => $items]);
    }

    /**
     * Creates a new service category entity.
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    #[Route('/new', name: 'admin_service_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $service = new Service();

        $form = $this->createForm(ServiceType::class, $service)
            ->add('saveAndCreateNew', SubmitType::class)
        ;

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            /** @var SubmitButton $submit */
            $submit = $form->get('saveAndCreateNew');

            if ($submit->isClicked()) {
                return $this->redirectToRoute('admin_service_new');
            }

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('admin/service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }


    /**
     * Finds and displays a Post entity.
     */
    #[Route('/{id<\d+>}', name: 'admin_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {

        return $this->render('admin/service/show.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     */
    #[Route('/{id<\d+>}/edit', name: 'admin_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'service.updated_successfully');

            return $this->redirectToRoute('admin_service_edit', ['id' => $service->getId()]);
        }

        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Post entity.
     */
    #[Route('/{id}/delete', name: 'admin_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_service_index');
        }


        $entityManager->remove($service);
        $entityManager->flush();

        $this->addFlash('success', 'service.deleted_successfully');

        return $this->redirectToRoute('admin_service_index');
    }
}
