<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use function rand;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    private const USERS =[
      [
          'username' => 'admin',
          'email'    => 'admin@blog.com',
          'name'     => 'Cornel Siclovan',
          'password' => 'secret123#'
      ],
      [
          'username' => 'john_doe',
          'email'    => 'john@blog.com',
          'name'     => 'John Doe',
          'password' => 'secret123#'
      ],
      [
          'username' => 'rob_smith',
          'email'    => 'rob_smith@blog.com',
          'name'     => 'Rob Smith',
          'password' => 'secret123#'
      ],
      [
          'username' => 'jenny_rowling',
          'email'    => 'jenny_rowling@blog.com',
          'name'     => 'Jenny Rowling',
          'password' => 'secret123#'
      ]
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference('user_admin');

        for($i=0; $i<100; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear());
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($this->getRandomUserReference());
            $blogPost->setSlug($this->faker->slug());

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

        for($i = 0; $i < 100; $i++){
            for($j = 0; $j < rand(1, 10); $j++){
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear());
                $comment->setAuthor($this->getRandomUserReference());

                /** @var BlogPost $blogPost */
                $blogPost = $this->getReference("blog_post_$i");
                $comment->setBlogPost($blogPost);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach(self::USERS as $userFixture){
            $user = new User();

            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userFixture['password']
            ));
            $this->addReference('user_'.$userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getRandomUserReference(): User
    {
        /** @var User $user */
        $user = $this->getReference('user_' . self::USERS[rand(0, 3)]['username']);

        return $user;
    }

}
