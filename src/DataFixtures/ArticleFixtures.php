<?php

namespace App\DataFixtures;


use App\Entity\Articles;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // Category
        for ($i = 1; $i <= 6; $i++)
        {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setUsername($faker->userName());
            $user->setPassword($faker->password());
            $user->setCreated($faker->dateTimeThisYear());
            $manager->persist($user);

            $category = new Category();
            $category->setTitle($faker->sentence());
            $manager->persist($category);

            // Article
            for ($j = 1; $j <= 12; $j++)
            {
                $article = new Articles();
                $article->setTitle($faker->sentence());
                $article->setContent($faker->text(700));
                $article->setImg($j.'.jpg');
                $article->setCreated($faker->dateTimeThisYear());
                $article->setCategory($category);
                $article->setUser($user);

                $manager->persist($article);
            }
        }
        $manager->flush();
    }
}
