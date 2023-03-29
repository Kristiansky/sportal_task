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
    public function index(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->allPosts($request->query->get('active') ?? 1, $request->query->get('page') ?? 1, $request->query->get('day'));

        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'publish_at' => $post->getPublishedAt() != null ? $post->getPublishedAt()->format('Y-m-d H:i:s') : null,
                'status' => $post->isStatus(),
            ];
        }
        if($request->query->get('format') == 'csv') {
            $fp = fopen('php://temp', 'w');
            foreach ($data as $datum) {
                fputcsv($fp, $datum);
            }
            rewind($fp);
            $response = new Response(stream_get_contents($fp));
            fclose($fp);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

            return $response;
        } elseif ($request->query->get('format') == 'xml') {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<posts>';
            foreach($data as $datum){
                $xml .= '<post>';
                $xml .=     '<title>';
                $xml .=         $datum['title'];
                $xml .=     '</title>';
                $xml .=     '<content>';
                $xml .=         htmlspecialchars($datum['content'], ENT_XML1, 'UTF-8');
                $xml .=     '</content>';
                $xml .=     '<created_at>';
                $xml .=         $datum['created_at'];
                $xml .=     '</created_at>';
                $xml .=     '<publish_at>';
                $xml .=         $datum['publish_at'];
                $xml .=     '</publish_at>';
                $xml .=     '<status>';
                $xml .=         $datum['status'];
                $xml .=     '</status>';
                $xml .= '</post>';
            }
            $xml .= '</posts>';

            $response = new Response($xml);
            $response->headers->set('Content-Type', 'xml');

            return $response;
        }
        return $this->json($data);
    }

    /**
     * @Route("/post", name="post_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($request->request->get('title') == null){
            return $this->json('Please provide Post title');
        }
        if($request->request->get('content') == null){
            return $this->json('Please provide Post content');
        }

        $post = new Post();
        $post->setTitle($request->request->get('title'));
        $post->setContent($request->request->get('content'));
        $post->setStatus(0);
        $post->setCreatedAt(new \DateTime());

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json('Created new post successfully with id ' . $post->getId());
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
            return $this->json('No post found for id ' . $id, 404);
        }

        $data = [
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'publish_at' => $post->getPublishedAt() != null ? $post->getPublishedAt()->format('Y-m-d H:i:s') : null,
            'status' => $post->isStatus(),
        ];

        return $this->json($data);
    }
}
