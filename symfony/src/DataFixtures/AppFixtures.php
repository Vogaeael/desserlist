<?php

namespace App\DataFixtures;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityType;
use App\Entity\Phrase;
use App\Entity\PhraseType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $categories = [
        'Default',
        'Random',
        'Family & Friendship',
        'Movie',
        'Music',
        'Sports',
        'Food',
        'Travel & Nature',
        'Technology',
        'Fashion',
        'Hobbies',
        'Weird',
    ];
    private $phraseTypes = [
        'Default',
        'Question',
        'Answer',
        'Statement',
        'Opinion',
        'Insult',

    ];
    private $personalityTypes = [
        'Default',
        'Formal',
        'Informal',
        'Flowery',
        'Mean',
        'Crazy',
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->createUserFixtures($manager);
        $this->createCategoryFixtures($manager);
        $this->createPhraseTypeFixtures($manager);
        $this->createPersonalityTypeFixtures($manager);
        $this->createPersonalityFixtures($manager);
        $this->createBotFixtures($manager);
        $this->createPhraseFixtures($manager);
        $this->createPhraseToAlternativeFixtures($manager);
        $this->createPhraseToReplyFixtures($manager);
    }

    private function createUserFixtures(ObjectManager $manager)
    {
        /**
         * create test users
         */
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail('user_' . $i . '@test.de');
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'changeme123'
                )
            );
            $user->setName('user ' . $i);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createCategoryFixtures(ObjectManager $manager)
    {
        /**
         * create test categories
         */
        foreach ($this->categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            //$this->addReference($categoryName, $category);
        }

        $manager->flush();
    }

    private function createPhraseTypeFixtures(ObjectManager $manager)
    {
        /**
         * create test phraseTypes
         */
        foreach ($this->phraseTypes as $type) {
            $phraseType = new PhraseType();
            $phraseType->setName($type);

            $manager->persist($phraseType);
            //$this->addReference($type, $phraseType);

        }

        $manager->flush();
    }

    private function createPersonalityTypeFixtures(ObjectManager $manager)
    {
        /**
         * create test personalityTypes
         */
        foreach ($this->personalityTypes as $type) {
            $personalityType = new PersonalityType();
            $personalityType->setName($type);

            $manager->persist($personalityType);
            $this->addReference($type, $personalityType);
        }

        $manager->flush();
    }

    private function createPersonalityFixtures(ObjectManager $manager)
    {
        /**
         * create test personalities
         */
        for ($i = 0; $i < 3; $i++) {
            $personality = new Personality();

            // copy of type array
            $types = $this->personalityTypes;

            // get 2 different types
            $keyOne = array_rand($types);
            $typeOne = $types[$keyOne];
            unset($types[$keyOne]);
            $keyTwo = array_rand($types);
            $typeTwo = $types[$keyTwo];

            /** @var PersonalityType $refOne */
            $refOne = $this->getReference($typeOne);
            /** @var PersonalityType $refTwo */
            $refTwo = $this->getReference($typeTwo);

            $personality->setName($typeOne . '-' . $typeTwo);
            $personality->setPersonalityTypeOne($refOne);
            $personality->setPersonalityTypeTwo($refTwo);

            $manager->persist($personality);
            $this->addReference('personality' . $i, $personality);
        }

        $manager->flush();
    }

    private function createBotFixtures(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; $i++) {
            $bot = new Bot();

            $bot->setName('bot' . $i);
            $bot->setEnabled(true);

            date_default_timezone_set('Europe/Berlin');
            $bot->setCreatedAt(new \DateTime());

            /** @var Personality $personality */
            $personality = $this->getReference('personality' . $i);
            $bot->setPersonality($personality);

            $manager->persist($bot);
            //$this->addReference('bot'.$i, $bot);
        }

        $manager->flush();
    }
    //
    //    private function createPhraseFixtures(ObjectManager $manager)
    //    {
    //        foreach ($this->personalityTypes as $personalityTypeName) {
    //            foreach ($this->categories as $categoryName) {
    //                foreach ($this->phraseTypes as $phraseTypeName) {
    //                    for($i = 0; $i < 10; $i++) {
    //                        $phrase = new Phrase()
    //                    }
    //                }
    //            }
    //        }
    //
    //
    //        $manager->flush();
    //    }

    private function createPhraseToAlternativeFixtures(ObjectManager $manager)
    {
        $manager->flush();
    }

    private function createPhraseToReplyFixtures(ObjectManager $manager)
    {
        $manager->flush();
    }
}
