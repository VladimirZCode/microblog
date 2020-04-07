<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.07.19
 * Time: 12:03
 */

namespace App\Controller;

use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as TwigEnvironment;

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

    public function __construct(
        TwigEnvironment $twig,
        MicroPostRepository $microPostRepository,
        RouterInterface $router
    ) {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->router = $router;
    }

    /**
     * @Route("/", name="micro_post_index")
     * @return Response
     */
    public function index()
    {
        $html = $this->twig->render('micro_post/index.html.twig', [
            'posts' => $this->microPostRepository->findAll()
        ]);

        return new Response($html);
    }

    /**
     * @Route("/add", name="micro_post_add")
     */
    public function add()
    {
        
    }

}
