<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.07.19
 * Time: 12:03
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as TwigEnvironment;

/**
 * @Route("/blog")
 */
class BlogController
{
    /**
     * @var TwigEnvironment
     */
    private $twig;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        TwigEnvironment $twig,
        SessionInterface $session,
        RouterInterface $router
    )
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="blog_index")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $html = $this->twig->render('blog/index.html.twig', [
            'posts' => $this->session->get('posts')
        ]);

        return new Response($html);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts = $this->session->get("posts");
        $posts[uniqid()] = [
            'title' => 'A random title' . rand(1, 500),
            'text' => 'Some random text number ' . rand(1, 500),
            'date' => new \DateTime()
        ];
        $this->session->set("posts", $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     * @param $id
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show($id)
    {
        $posts = $this->session->get("posts");

        if (!isset($posts[$id])) {
            throw new NotFoundHttpException("Post not found");
        }

        $html = $this->twig->render('blog/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id]
        ]);

        return new Response($html);
    }
}
