<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use App\Entity\User;
use App\Factory\BlogPostFactory;
use App\Repository\BlogCategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BlogPostFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use DataFixturesTrait;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->faker->seed(1234);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BlogCategoryFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['all'];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);
        $blogPostsUsers = $userRepository->findUsersByRole(User::ROLE_BLOGGER);

        /** @var BlogCategoryRepository $blogCategoryRepository */
        $blogCategoryRepository = $manager->getRepository(BlogCategory::class);
        $blogCategories = $blogCategoryRepository->findAll();

        // Create demo blogPost posts with specified blogPostId for API testing
        BlogPostFactory::createSequence([
            [
                'title' => 'Demo blog post 1',
                'summary' => 'Demo blog post 1 summary. '.$this->getRandomText(200),
                'content' => 'Demo blog post 1 content. '.$this->generateParagraphs(),
                'status' => BlogPost::STATUS_PUBLISHED,
                'slug' => 'welcome-to-the-demo-blog-post',
                'createdAt' => $this->faker->dateTimeInInterval('- 299 day', '+ 0 day'),
                'updatedAt' => $this->faker->dateTimeInInterval('- 299 day', '+ 0 day'),
                'author' => $userRepository->findOneBy(['email' => 'blogauthor1@example.com']),
                'blogCategory' => $blogCategoryRepository->findOneBy(['name' => 'Travel']),
            ],
            [
                'title' => 'Demo blog post 2',
                'summary' => 'Demo blog post 2 summary. '.$this->getRandomText(200),
                'content' => 'Demo blog post 2 content. '.$this->generateParagraphs(),
                'status' => BlogPost::STATUS_PUBLISHED,
                'slug' => 'welcome-to-the-demo-blog-post-api-testing',
                'createdAt' => $this->faker->dateTimeInInterval('- 298 day', '+ 0 day'),
                'updatedAt' => $this->faker->dateTimeInInterval('- 298 day', '+ 0 day'),
                'author' => $userRepository->findOneBy(['email' => 'blogauthor2@example.com']),
                'blogCategory' => $blogCategoryRepository->findOneBy(['name' => 'Leisure']),
            ],
        ]);

        BlogPostFactory::createSequence(
            function () use ($blogCategories, &$blogPostsUsers) {
                $totalBlogPosts = 298;
                $startDay = 300;

                foreach (range(1, $totalBlogPosts) as $i) {
                    $user = $blogPostsUsers[array_rand($blogPostsUsers)];
                    $blogCategory = $blogCategories[array_rand($blogCategories)];
                    $date = '-'.($startDay - $i).' days';

                    $blogPost = [
                        'title' => $this->faker->sentence($this->faker->numberBetween(5, 7)),
                        'summary' => $this->getRandomText(),
                        'content' => $this->generateParagraphs(),
                        'status' => BlogPost::STATUS_PUBLISHED,
                        'slug' => $this->faker->slug(),
                        'createdAt' => $this->faker->dateTimeInInterval($date, '+ 0 day'),
                        'updatedAt' => $this->faker->dateTimeInInterval($date, '+ 0 day'),
                        'author' => $user,
                        'blogCategory' => $blogCategory,
                    ];

                    yield $blogPost;
                }
            }
        );
    }
}
