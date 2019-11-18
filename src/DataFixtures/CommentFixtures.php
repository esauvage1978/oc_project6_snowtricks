<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Service\CommentManager;
use App\Service\TrickManager;
use App\Validator\TrickValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var CommentManager
     */
    private $commentManager;

    public function __construct(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
        $this->faker = new Factory();
        $this->faker = $this->faker->create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($j = 0; $j < count(TrickFixtures::DATA); $j++) {
            for ($i = 0; $i < mt_rand(20, 40); $i++) {

                $comment = new Comment();
                $comment
                    ->setCreatedAt($this->faker->dateTimeBetween('-6 months'))
                    ->setContent(join($this->faker->paragraphs(mt_rand(1, 6))));

                $this->commentManager->update(
                    $comment,
                    $this->getReference(
                        'trick-' . $j),
                    $this->getReference(
                        'user-' . mt_rand(0,
                            count(UserFixtures::DATA) - 1
                        )));
            }
        }

    }


    private function initialise(Trick $instance, $data): Trick
    {

         {

            $instance->addComment($comment);
        }
        return $instance;
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
            UserFixtures::class
        ];
    }
}