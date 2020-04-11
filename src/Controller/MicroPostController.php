<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.07.19
 * Time: 12:03
 */

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as TwigEnvironment;
use DateTime;

/**
 * @Route("/micro-post")
 */
class MicroPostController
{
    /**
     * @var TwigEnvironment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var FlashBagInterface $flashBag;
     */
    private $flashBag;

    /**
     * MicroPostController constructor.
     * @param TwigEnvironment $twig
     * @param MicroPostRepository $microPostRepository
     * @param RouterInterface $router
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        TwigEnvironment $twig,
        MicroPostRepository $microPostRepository,
        RouterInterface $router,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag
    ) {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="micro_post_index")
     * @return Response
     */
    public function index(): Response
    {
        $html = $this->twig->render('micro_post/index.html.twig', [
            'posts' => $this->microPostRepository->findBy([], ['time' => 'DESC'])
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{id}", name="micro_post_post", requirements={"id"="\d+"})
     * @param MicroPost $post
     * @return Response
     */
    public function post(MicroPost $post): Response
    {
        return new Response($this->twig->render('micro_post/post.html.twig', [
            'post' => $post
        ]));
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $microPost = new MicroPost();
        $microPost->setTime(new DateTime());
        $microPost->setText('');

        $addMicroPostForm = $this->formFactory->create(MicroPostType::class, $microPost);
        $addMicroPostForm->handleRequest($request);

        if ($addMicroPostForm->isSubmitted() && $addMicroPostForm->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return new Response(
            $this->twig->render('micro_post/add.html.twig', [
                'addMicroPostForm' => $addMicroPostForm->createView()
            ])
        );
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @param MicroPost $microPost
     * @param Request $request
     * @return Response
     */
    public function edit(MicroPost $microPost, Request $request): Response
    {
        $addMicroPostForm = $this->formFactory->create(MicroPostType::class, $microPost);
        $addMicroPostForm->handleRequest($request);

        if ($addMicroPostForm->isSubmitted() && $addMicroPostForm->isValid()) {
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return new Response(
            $this->twig->render('micro_post/add.html.twig', [
                'addMicroPostForm' => $addMicroPostForm->createView()
            ])
        );
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @param MicroPost $microPost
     * @return Response
     */
    public function delete(MicroPost $microPost): Response
    {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();

        $this->flashBag->add('notice', 'The post was deleted.');

        return new RedirectResponse($this->router->generate('micro_post_index'));
    }

}
