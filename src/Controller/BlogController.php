<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 18/02/2019
 * Time: 14:55
 */

namespace App\Controller;



use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 1}, requirements={"page"="\d+"})
     */
    public function list($page, Request $request)
    {
        $limit = $request->get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();


        return $this->json(
            [
                'page'  => $page,
                'limit' => $limit,
                'data'  => array_map(function(BlogPost $item){
                    return $this->generateUrl(  'post_by_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="post_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App\Entity\BlogPost")
     */
    public function post($post)
    {
        return $this->json(
          $post
        );
    }

    /**
     * @Route("/post/{slug}", name="post_by_slug", methods={"GET"})
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}}, class="App\Entity\BlogPost")
     */
    public function postBySlug($post)
    {
        return $this->json(
            $post
        );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}