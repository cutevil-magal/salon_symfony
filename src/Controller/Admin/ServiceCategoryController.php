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
use App\Entity\ServiceCategory;
use App\Entity\User;
use App\Form\PostType;
use App\Form\ServiceCategoryType;
use App\Repository\PostRepository;
use App\Repository\ServiceCategoryRepository;
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

#[Route('/admin/service-category')]
#[IsGranted('ROLE_ADMIN')]
class ServiceCategoryController extends AbstractController
{
    /**
     * Lists all Post entities.
     *
     * This controller responds to two different routes with the same URL:
     *   * 'admin_post_index' is the route with a name that follows the same
     *     structure as the rest of the controllers of this class.
     *   * 'admin_index' is a nice shortcut to the backend homepage. This allows
     *     to create simpler links in the templates. Moreover, in the future we
     *     could move this annotation to any other controller while maintaining
     *     the route name and therefore, without breaking any existing link.
     */
    #[Route('/', name: 'admin_service_category_index', methods: ['GET'])]
    public function index(
        #[CurrentUser] User $user,
        ServiceCategoryRepository $categories,
    ): Response {
        $items = $categories->findAll();

        return $this->render('admin/category/index.html.twig', ['categories' => $items]);
    }

    /**
     * Creates a new service category entity.
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    #[Route('/new', name: 'admin_service_category_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $category = new ServiceCategory();

        $form = $this->createForm(ServiceCategoryType::class, $category)
            ->add('saveAndCreateNew', SubmitType::class)
        ;

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            /** @var SubmitButton $submit */
            $submit = $form->get('saveAndCreateNew');

            if ($submit->isClicked()) {
                return $this->redirectToRoute('admin_service_category_new');
            }

            return $this->redirectToRoute('admin_service_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

}
