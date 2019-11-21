<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Service\CommentManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    CONST DATA = [
        "Super",
        "Superbe figure !",
        "La figure est facile.",
        "Je conseille de mettre de la wax sur la planche",
        "Super",
        "Sick",
        "C'est si beau, merci !",
        "C possible de tomber en avant ou en arrière quand on fait le virage ?",
        "Bonne explication, j'approuve!! Le snowboard donne d'autre sensation que la ski",
        "Merci tu me sauve la vie",
        "C'est super bien expliquer merci",
        "Sa ma bien servi",
        "Filmer aux 2 Alpes ?",
        "Merci beaucoup",
        "Bravo super vidéo",
        "c'est quelle pieds qui doit étre evant quand t'es droitier",
        "super grace a toi je suis maintenant encore plus nul qu avant",
        "sa a l'aire si simple...",
        "naturellement",
        "Le snowboard c’est carrément chaud.",
        "Notez que les skaters on quelque facilité si ils se mettent au snow plutôt qu’au ski",
        "en skate frontside et backside sont inversés alors ?",
        "le snow a l'air vachement plus simple et jouissif que le ski",
        "faire du snowboard c'est plus facile pour ceux qui font du skate comme moi!",
        "Ça manque cruellement de détails et d'exercices intermédiaires pour aider un débutant. Sans parler de la progression en  2/2 jusqu'au virage coupé. De plus le gars est incapable de réaliser un virage coupé.",
        "Sa me parait quand même plus simple que le ski ! :s",
        "Eh ben non , il ne faut dérapé en snowboard mais utilisé les quarts, aucun tuto (en français) sur YT pour expliquer comment bien carvé en snow. Toujours ce fameux retard à la française encore",
        "Merci ca ma aideé",
        "Excellent ce Tuto !",
        "Cc est ce que plus dur grad olie que noze",
        "Merci a vous ! Manque plus que la neige ! (pas facile en auvergne !)",
        "ok c est vrai que j avais la sensation de tourner uniquement le jambes. Merci j essaye ça cette saison qui commence bientot",
        "Le mec son nom de famille c’est ledieu, non d’un chien comment est ce qu’un homme avec une telle lignée pourrait faire un mauvais tutoriel,  je m’abonne.",
        "bonjour, je fait du snow depuis 3 ans et gère sur les pistes mais niveau freestyle c plus dur. Sur les sauts je bloque souvent à 90° et n arrive pas a faire de 360. pouvez vous me dire pour quelle raison (probables) ?",
        "Salut les gars ! super tuto ! je me demande  si tous ça est possible avec mes K2 press , qui ont les fixations en arrière et pas au millieu des skis ?",
        "Salut j'ai commencé le snow de pui 2 semaine et je gere que le petit saut mai les  big jump à la réception  c la merde comment faire ?",
        "Le noli est le non oli.",
        "Un grand bravo Cécile :):):) !!! et bienvenue au Club des Boarders ! A tout bientôt pour \'Isola 2000, le retour ! ..",
        "LE SNOWBOARD C'EST LE MEILLEURES SPORT DU MONDE",
        "EEEEExxxAaaactement",
        "Vive le snowboard !!!!!!!!",
        "Bonjour j'au une berton TWC en vert es que c une bonne planche pourvue freestyle",
        "au niveau des rotation je n'ai pas de problème mais des que je prend un peut plus de hauteur j'ai tendance a perdre l'équilibre qu'elqun pourrait me donner des conseille pour garder l'équilibre ?",
"Il faut quel niveau pour réussir c'est tricks ?",
        "Juste , backside et frontside sont inversé dans la vidéo",
        "dans quelle station vous skiez",
        "C est vraiment pas grave mais frontside c est les talons",
        "bas normalement c est les talons mais ca peut etre les deux mais un frontside 180 tu voie l aterrisage",
        "Si tu perfs l'équilibre c'est que tu peux améliorer ta trajectoire, ta façon de prendre la forme du S comme expliqué dans la vidéo sur le snowpark, ou que tu es trop  ou pas assé sur l'un ou l'autre des cares :)",
        "ok merci de la réponse je vais essayer différentes façons d'aborder mes saut pour voire",
        "Magnifique vidéo",
        "Tignes <3",

];

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
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($j = 0; $j < count(TrickFixtures::DATA); $j++) {
            for ($i = 0; $i < mt_rand(20, 40); $i++) {

                $comment = new Comment();
                $comment
                    ->setCreatedAt($this->faker->dateTimeBetween('-6 months'))
                    ->setContent(self::DATA[rand(0,count(self::DATA)-1)]);

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

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
            UserFixtures::class
        ];
    }
}
