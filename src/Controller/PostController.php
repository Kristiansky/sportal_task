<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;

/**
 * @Route("/api", name="api_")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/post", name="post_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->allPosts($request->query->get('active') ?? 1, $request->query->get('page') ?? 1, $request->query->get('day'));

        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'name' => $post->getTitle(),
                'description' => $post->getContent(),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/post", name="post_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle($request->request->get('title'));
        $post->setContent($request->request->get('content'));
        $post->setStatus(0);
        $post->setCreatedAt(new \DateTime());

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json('Created new project successfully with id ' . $post->getId());
    }

    /**
     * @Route("/post/{id}", name="post_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (!$post) {
            return $this->json('No project found for id' . $id, 404);
        }

        $data = [
            'id' => $post->getId(),
            'name' => $post->getTitle(),
            'description' => $post->getContent(),
        ];

        return $this->json($data);
    }
}
