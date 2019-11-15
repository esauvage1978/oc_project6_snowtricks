<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\CategoryManager;
use App\Validator\CategoryValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const DATA =
        [
            [
                'name' => 'grabs',
                'content' => '<p>Un <span style="background-color: rgb(255, 255, 0);"><b>grab </b></span>consiste à'
                    . ' attraper la planche avec la main pendant le saut. </p><p>Le verbe anglais to grab signifie '
                    . '« attraper. » </p>',
            ],
            [
                'name' => 'rotation',
                'content' => '<p>On désigne par le mot « <span style="background-color: rgb(255, 255, 0);">'
                    . '<b>rotation </b></span>» uniquement des rotations horizontales ;</p>'
                    . '<p> les rotations verticales sont des <b>flips</b>. </p>'
                    . '<p>Le principe est d\'effectuer une rotation horizontale pendant le saut,
                  puis d\'attérir en position switch ou normal</p>',
            ],
            [
                'name' => 'flip',
                'content' => '<p>Un <span style="background-color: rgb(255, 255, 0);"><b>flip </b></span>'
                    . 'est une rotation verticale. </p><p>On distingue les front flips, rotations en avant, et les back'
                    . ' flips, rotations en arrière.</p>',
            ],
            [
                'name' => 'Les rotations désaxées',
                'content' => '<p>Une <span style="background-color: rgb(255, 255, 0);"><b>rotation désaxée</b></span>'
                    . ' est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier'
                    . ' qui désaxe la rotation.</p><p> Il existe différents types de rotations désaxées '
                    . '(corkscrew ou cork, rodeo, misty, etc.) en fonction de la manière dont est lancé le buste. </p>'
                    . '<p>Certaines de ces rotations, bien qu\'initialement horizontales,'
                    . ' font passer la tête en bas.</p>',
            ],
            [
                'name' => 'Les slides',
                'content' => '<p>Un <span style="background-color: rgb(255, 255, 0);"><b>slide </b></span>consiste '
                    . 'à glisser sur une barre de slide.</p><p> Le slide se fait soit avec la planche dans l\'axe '
                    . 'de la barre, soit perpendiculaire, soit plus ou moins désaxé.</p>',
            ],
            [
                'name' => 'Les one foot tricks',
                'content' => '<p>Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe'
                    . ' correspondante pour mettre en évidence le fait que le pied n\'est pas fixé. </p><p>'
                    . '<span style="background-color: rgb(239, 239, 239);"><span style="color: rgb(255, 0, 0);">'
                    . 'Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise'
                    . ' réception. </span></span></p>',
            ],
            [
                'name' => 'Old school',
                'content' => 'Le terme <b><span style="background-color: rgb(255, 255, 0);">old school</span></b>'
                    . '  désigne un style de freestyle caractérisée par en ensemble de figure et une manière de réaliser'
                    . '  des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990'
                    . '  (par opposition à new school).',
            ],
        ];

    /**
     * @var CategoryManager
     */
    private $manager;

    /**
     * @var CategoryValidator
     */
    private $validator;


    public function __construct(CategoryValidator $validator)
    {
        $this->validator = $validator;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::DATA); ++$i) {
            $entity = new Category();

            $instance = $this->initialise(new Category(), self::DATA[$i]);

            $this->checkAndPersist($manager, $instance);

            $this->saveReference($instance, $i);
        }

        $manager->flush();
    }

    private function saveReference(Category $category, int $i)
    {
        $this->addReference('category-' . $i, $category);
    }

    private function checkAndPersist(ObjectManager $manager, Category $instance)
    {
        if ($this->validator->isValid($instance)) {
            $manager->persist($instance);
        }
    }

    private function initialise(Category $instance, $data): Category
    {
        $instance
            ->setName($data['name'])
            ->setContent($data['content']);

        return $instance;
    }
}
