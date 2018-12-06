<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($j=1; $j<=3; $j++) {
            $category = new Category();
            $category->setTitle($faker->sentence());
            $category->setDescription($faker->paragraph());
            $manager->persist($category);
            for ($i = 1; $i <= mt_rand(4, 6); $i++) {
                $article = new Article();

                $content="<p>".join($faker->paragraphs(5), "</p><p>")."</p>";
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category) ;
                $manager->persist($article);
                for($k=1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();
                    $content="<p>".join($faker->paragraphs(2), "</p><p>")."</p>";
                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days=$interval->days;
                    $min='-'.$days." months";
                    $comment->setCreatedAt($faker->dateTimeBetween($min));
                    $comment->setContent($content);
                    $comment->setArticle($article);
                    $comment->setAuthor($faker->name());

                    $manager->persist($comment);

                }
            }
        }

        $manager->flush();
    }
}
