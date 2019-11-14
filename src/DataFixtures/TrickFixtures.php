<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Service\TrickManager;
use App\Validator\TrickValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    const DATA =
        [
            [
                'name' => 'Rotation frontside et backside',
                'content' => '<p>Un snowboarder peut faire des rotations déclenchées du côté de ses pointes de pied, '
                    . 'en <b>frontside </b>ou de ses talons, en <b>backside</b>. On parle aussi de frontside'
                    . ' et backside pour les murs de halfpipe et les hips.</p><p> Les rotations vont du demi-tour'
                    . ' en <b>180 degrés</b> jusqu\'à des <b>1800 degrés</b>, soit cinq tours !</p>',
            ],
            [
                'name' => 'Switch',
                'content' => '<p>Lorsque l\'on ride de son mauvais côté, tous les noms de figures sont précédées de la
 dénomination switch. Un regular fera donc ses tricks en switch, comme un goofie, et inversement.</p>',
            ],
            [
                'name' => 'Backside air',
                'content' => '<p>Le grab star du snowboard qui peut être fait d\'autant de façon différentes qu\'il y a de styles de riders. Il consiste à attraper la carre arrière entre les pieds, ou légèrement devant, et à pousser avec sa jambe arrière pour ramener la planche devant. C\'est une figure phare en pipe ou sur un hip en backside. C\'est généralement avec ce trick que les riders vont le plus haut.</p>',
            ],
            [
                'name' => 'Mc Twist',
                'content' => '<p>Un grand classique des rotations tête en bas qui se fait en backside, sur un mur backside de pipe. Le Mc Twist est généralement fait en japan, un grab très tweaké (action d\'accentuer un grab en se contorsionnant).</p>',
            ],
            [
                'name' => 'Crippler',
                'content' => '<p>Une autre rotation tête en bas classique qui s\'apparente à un backflip sur un mur frontside de pipe ou un quarter.</p>',
            ],
            [
                'name' => 'Backside rodeo',
                'content' => '<p>Une rotation tête en bas backside tournant dans le sens d\'un backflip qui peut se faire aussi bien sur un kicker, un pipe ou un hip.</p>',
            ],
            [
                'name' => 'Air to fakie',
                'content' => 'En pipe, sur un quarter ou un hip, ce terme désigne un saut sans rotation où le rider retombe dans le sens inverse..',
            ],
            [
                'name' => 'Handplant',
                'content' => 'Un trick inspiré du skate qui consiste à tenir en équilibre sur une ou deux mains au sommet d\'une courbe. Existe avec de nombreuses variantes dans les grabs et les rotations.',
            ],
            [
                'name' => 'Cork',
                'content' => 'Le diminutif de corkscrew qui signifie littéralement tire-bouchon et désignait les premières simples rotations têtes en bas en frontside. Désormais, on utilise le mot cork à toute les sauces pour qualifier les figures où le rider passe la tête en bas, peu importe le sens de rotation. Et dorénavant en compétition, on parle souvent de double cork, triple cork et certains riders vont jusqu\'au quadruple cork !',
            ],
            [
                'name' => '270',
                'content' => 'Désigne le degré de rotation, soit 3/4 de tour, fait en entrée ou en sortie sur un jib. Certains riders font également des rotations en 450 degrés avant ou après les jibs.',
            ],
            [
                'name' => 'Revert',
                'content' => 'Un revert n\'est pas une figure à part entière mais c\'est le fait de continuer à tourner sur la neige après une rotation aérienne. Cela montre ainsi que la rotation n\'est pas contrôlée et ça fait perdre des points en compétition.',
            ],
            [
                'name' => 'Backside Triple Cork 1440',
                'content' => 'En langage snowboard, un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d\'épaules effectué juste au moment du saut. Le tout premier Triple Cork a été plaqué par Mark McMorris en 2011, lequel a récidivé lors des Winter X Games 2012... avant de se faire voler la vedette par Torstein Horgmo, lors de ce même championnat. Le Norvégien réalisa son propre Backside Triple Cork 1440 et obtint la note parfaite de 50/50.',
            ],
            [
                'name' => 'Method Air',
                'content' => 'Cette figure – qui consiste à attraper sa planche d\'une main et le tourner perpendiculairement au sol – est un classique "old school". Il n\'empêche qu\'il est indémodable, avec de vrais ambassadeurs comme Jamie Lynn ou la star Terje Haakonsen. En 2007, ce dernier a même battu le record du monde du "air" le plus haut en s\'élevant à 9,8 mètres au-dessus du kick (sommet d\'un mur d\'une rampe ou autre structure de saut).',
            ],
            [
                'name' => 'Double Backflip One Foot',
                'content' => 'Comme on peut le deviner, les "one foot" sont des figures réalisées avec un pied décroché de la fixation. Pendant le saut, le snowboarder doit tendre la jambe du côté dudit pied. L\'esthète Scotty Vine – une sorte de Danny MacAskill du snowboard – en a réalisé un bel exemple avec son Double Backflip One Foot.',
            ],
            [
                'name' => 'Double Mc Twist 1260',
                'content' => 'Le Mc Twist est un flip (rotation verticale) agrémenté d\'une vrille. Un saut très périlleux réservé aux professionnels. Le champion précoce Shaun White s\'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010. Nul doute que c\'est cette figure qui lui a valu de remporter la médaille d\'or.',
            ],
            [
                'name' => 'Double Backside Rodeo 1080',
                'content' => 'À l\'instar du cork, le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé. Un des plus beaux de l\'histoire est sans aucun doute le Double Backside Rodeo 1080 effectué pour la première fois en compétition par le jeune prodige Travis Rice, lors du Icer Air 2007. La pirouette est tellement culte qu\'elle a fini dans un jeu vidéo où Travis Rice est l\'un des personnages.',
            ],
            [
                'name' => 'Japan',
                'content' => 'saisie du ski opposé (main droite qui attrape le ski gauche, ou l’inverse) à l’arrière de la fixation, en passant derrière la jambe, souvent en repliant la jambe grabée.',
            ],
            [
                'name' => 'Tail',
                'content' => 'on prend le ski tout à l’arrière et les skis se croisent devant',
            ],
            [
                'name' => 'Safety',
                'content' => 'on attrape le ski au niveau de la chaussure ou de la fixation avant. Les jambes restent groupées, les skis restent parallèles pendant la saisie.',
            ],
            [
                'name' => 'Mute',
                'content' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
            ],
            [
                'name' => 'stalefish',
                'content' => 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière',
            ],
        ];

    /**
     * @var TrickManager
     */
    private $manager;

    /**
     * @var TrickValidator
     */
    private $validator;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var TrickManager
     */
    private $trickManager;

    public function __construct(TrickValidator $validator, TrickManager $trickManager)
    {
        $this->validator = $validator;
        $this->trickManager = $trickManager;
        $this->faker = new Factory();
        $this->faker = $this->faker->create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::DATA); ++$i) {
            $entity = new Trick();

            $instance = $this->initialise(new Trick(), self::DATA[$i]);

            $this->checkAndPersist($manager, $instance);
        }

        $manager->flush();
    }

    private function checkAndPersist(ObjectManager $manager, Trick $instance)
    {
        $this->trickManager->initialise($instance);
        if ($this->validator->isValid($instance)) {
            $manager->persist($instance);
        }
    }

    private function initialise(Trick $instance, $data): Trick
    {
        $instance
            ->setName($data['name'])
            ->setContent($data['content'])
            ->setCreateAt($this->faker->dateTimeBetween('-6 months'));
        for ($i = 0; $i < mt_rand(2, 6); $i++) {

            $instance->addCategory($this->getReference(
                'category-' . mt_rand(0,
                    count(CategoryFixtures::DATA)-1
                )));

        }
        return $instance;
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}
