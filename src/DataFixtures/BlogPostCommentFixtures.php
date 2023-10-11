<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\BlogPostComment;
use App\Entity\User;
use App\Factory\BlogPostCommentFactory;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BlogPostCommentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
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
            BlogPostFixtures::class,
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
        $blogPostsUsers = $userRepository->findUsersByRole(User::ROLE_USER);

        // Create demo blogPost post comments with specified blogPostCommentId for E2E testing
        BlogPostCommentFactory::createSequence([
            [
                'content' => 'Demo blog post comment 1. '.$this->faker->paragraph(),
                'status' => BlogPostComment::STATUS_PUBLISHED,
                'createdAt' => $this->faker->dateTimeInInterval('- 290 day', '+ 0 day'),
                'updatedAt' => $this->faker->dateTimeInInterval('- 290 day', '+ 0 day'),
                'blogPost' => $manager->getRepository(BlogPost::class)->findOneBy(['title' => 'Demo blog post 1']),
                'author' => $userRepository->findOneBy(['email' => 'user1@example.com']),
            ],
            [
                'content' => 'Demo blog post comment 2. '.$this->faker->paragraph(),
                'status' => BlogPostComment::STATUS_PUBLISHED,
                'createdAt' => $this->faker->dateTimeInInterval('- 290 day', '+ 0 day'),
                'updatedAt' => $this->faker->dateTimeInInterval('- 290 day', '+ 0 day'),
                'blogPost' => $manager->getRepository(BlogPost::class)->findOneBy(['title' => 'Demo blog post 2']),
                'author' => $userRepository->findOneBy(['email' => 'user2@example.net']),
            ],
        ]);

        $blogPosts = $manager->getRepository(BlogPost::class)->findAll();

        // Loop through all blogPosts and add comments
        foreach ($blogPosts as $blogPost) {
            $totalComments = $this->faker->numberBetween(0, 3);

            $addDays = 2;
            foreach (range(1, $totalComments) as $i) {
                $user = $blogPostsUsers[array_rand($blogPostsUsers)];
                /** @var \DateTimeInterface $createdAt */
                $createdAt = $blogPost->getCreatedAt();
                $currentDate = new \DateTimeImmutable();
                $interval = $currentDate->diff($createdAt);
                $daysAgo = $interval->days;
                $commentDate = $this->faker->dateTimeInInterval('- '.$daysAgo.' day', '+ '.$addDays.' day');

                $comment = [
                    'content' => $this->faker->paragraph(),
                    'status' => BlogPostComment::STATUS_PUBLISHED,
                    'createdAt' => $commentDate,
                    'updatedAt' => $commentDate,
                    'blogPost' => $blogPost,
                    'author' => $user,
                ];

                BlogPostCommentFactory::new()->create($comment);

                ++$addDays;
            }
        }
    }
}
