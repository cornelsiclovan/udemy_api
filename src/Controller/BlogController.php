<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 18/02/2019
 * Time: 14:55
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [[
            'id'    => 1,
            'slug'  => 'hello-world',
            'title' => 'Hello world!'
        ],
        [
            'id'    => 2,
            'slug'  => 'another-post',
            'title' => 'Another post'
        ],
        [
            'id'    => 3,
            'slug'  => 'last-example',
            'title' => 'Last example'
        ],
    ];

    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 1})
     */
    public function list($page)
    {
        return new JsonResponse(
            [
                'page' => $page,
                'data' => array_map(function($item){
                    return $this->generateUrl(  'post_by_slug', ['slug' => $item['slug']]);
                }, self::POSTS)
            ]
        );
    }

    /**
     * @Route("/{id}", name="post_by_id", requirements={"id"="\d+"})
     */
    public function post($id)
    {
        return new JsonResponse(
            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="post_by_slug")
     */
    public function postBySlug($slug)
    {
        return $this->json(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
        );
    }

}