<?php

namespace App\DataFixtures;

use App\Entity\Entry;
use App\Entity\Meal;
use App\Entity\User;
use App\Entity\Workday;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $meals = [
        'Burgerlasagne'                                     => 'http://www.langwacken.de/grillkulturverein/burger-lasagne/',
        'Pachamanca'                                        => 'https://blog.viventura.de/pachamanca/',
        'Salat-Smoothie mit Gojibeeren'                     => 'https://www.chefkoch.de/rezepte/2693631422113972/Salat-Smoothie-mit-Gojibeeren.html',
        'Drachenhackfleisch à la Dora'                      => 'https://www.chefkoch.de/rezepte/1228271227962981/Drachenhackfleisch-la-Dora.html',
        'Drachenfleisch-Salat mit Öldressing und Plumbo'    => 'http://aiondatabase.net/de/item/152231189/',
        'Philadelphia - Ananas - Torte'                     => 'https://www.chefkoch.de/rezepte/846031190029891/Philadelphia-Ananas-Torte.html',
        'Brokkoli-Creme'                                    => 'https://www.chefkoch.de/rezepte/2384121377787635/Brokkoli-Creme.html',
        'Möhren-Gratin'                                     => 'https://www.chefkoch.de/rezepte/2001321323969084/Moehren-Gratin.html',
        'Currysoße für Currywurst'                          => 'https://www.chefkoch.de/rezepte/2059121333101564/Currysosse-fuer-Currywurst.html',
        'Griechische Zitronensuppe mit Hack - Reisbällchen' => 'https://www.chefkoch.de/rezepte/1242261229011350/Griechische-Zitronensuppe-mit-Hack-Reisbaellchen.html',
        'Lockere Erdbeertorte'                              => 'https://www.chefkoch.de/rezepte/2794561431614383/Lockere-Erdbeertorte.html',
        'Asiatische Gemüsesuppe - pikant'                   => 'https://www.chefkoch.de/rezepte/1092421215259171/Asiatische-Gemuesesuppe-pikant.html',
        'Gekochte Elfenaugen' => null,
        'Verbrantes Handy' => null,
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        shuffle($this->meals);
        $this->createUserFixtures($manager);
        $this->createMealFixtures($manager);
        $this->createWorkdayFixtures($manager);
        $this->createEntryFixtures($manager);
    }

    private function createUserFixtures(ObjectManager $manager)
    {
        $numberUser = 9;
        /**
         * create test users
         * if you go beyond 26 you should expand the range of letters
         */
        for ($i = 0; $i < $numberUser; ++$i) {
            $user = new User();
            $letters = range('a', 'z');
            $name = 'dude-' . $letters[$i];
            $user->setEmail($name . '@test.com');
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'auto123'
                )
            );
            $user->setName($name);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createMealFixtures(ObjectManager $manager)
    {
        /**
         * create test meals
         */
        foreach ($this->meals as $mealName => $mealLink) {
            $meal = new Meal();
            $meal->setName($mealName);
            $meal->setLink($mealLink);
            if ($this->getRandomBool()) {
                $meal->setDescription($this->getRandomString(100));
            }

            $manager->persist($meal);
        }

        $manager->flush();
    }

    private function createWorkdayFixtures(ObjectManager $manager)
    {
        $meals = $manager->getRepository(Meal::class)->findAll();
        /**
         * create test phraseTypes
         */
        $period = new DatePeriod(
            new DateTime('2020-03-01'),
            new DateInterval('P1D'),
            new DateTime('2020-03-31')
        );

        /** @var DateTime $date */
        foreach ($period as $date) {
            $workday = new Workday();
            $workday->setDate($date);
            $workday->setMeal($this->getRandomFromArray($meals));

            $manager->persist($workday);
        }

        $manager->flush();
    }

    private function createEntryFixtures(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $workdays = $manager->getRepository(Workday::class)->findAll();
        $minEntries = round(count($workdays) / 2);
        $maxEntries = round(count($workdays) * 0.9);
        $minNoteLength = 5;
        $maxNoteLength = 10;
        /**
         * create test personalityTypes
         */
        foreach ($users as $user) {
            for ($i = 0; $i < rand($minEntries, $maxEntries); ++$i) {
                $entry = new Entry();
                $entry->setUser($user);
                if ($this->getRandomBool()) {
                    $entry->setNote($this->getRandomString(rand($minNoteLength, $maxNoteLength)));
                }
                $entry->setWorkday($this->getRandomFromArray($workdays));

                $manager->persist($entry);
            }
        }

        $manager->flush();
    }

    /**
     * Get an random string of the length
     *
     * @param int $length
     *
     * @return string
     */
    private function getRandomString(int $length = 10): string
    {
        $characters = ' .,0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Get random an value of the array
     *
     * @param array $array
     *
     * @return mixed
     */
    private function getRandomFromArray(array $array)
    {
        return $array[rand(0, count($array) - 1)];
    }

    /**
     * Get random true or false
     *
     * @return bool
     */
    private function getRandomBool()
    {
        return (boolean)rand(0, 1);
    }
}
