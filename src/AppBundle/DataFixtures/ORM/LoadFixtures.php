<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\City;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Nelmio\Alice\Fixtures;
use Symfony\Component\DomCrawler\Crawler;

class LoadFixtures implements ORMFixtureInterface
{
    const CITY_URL = 'https://ro.wikipedia.org/wiki/Lista_ora%C8%99elor_din_Rom%C3%A2nia';
    const CAR_URL = "https://en.wikipedia.org/wiki/List_of_Volkswagen_passenger_vehicles";

    /** @var EntityManagerInterface $em */
    private $em;
    /** @var array */
    private $cities;
    /** @var array */
    private $cars;
    /** @var array */
    private $prices;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        $this->extractCities();
        $this->extractCars();

        for ($i=1; $i<=500; $i++) {
            for ($j=1; $j <=4; $j++) {
                $this->prices[$i][$j] = floatval(rand(3000,50000)/100);
            }
        }
    }

    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__ . '/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function imageForCity($current)
    {
        return $this->cities[$current][1];
    }

    public function nameForCity($current)
    {
        return $this->cities[$current][0];
    }

    public function cityForBuilding($array, $current)
    {
        return $array[$current % 50];
    }

    public function nameForBuilding($array, $current)
    {
        $data = [
            "Hotel Intercontinental",
            'Hotel Ibis',
            'Hotel Belvedere',
            'Hotel Orizont',
            'Hotel Victoria',
            'Pensiunea Transilvania',
            'Casa Rosenau',
            'Pensiunea Valea Cetatii',
            'Casa Amphora',
            'Casa Zen'
        ];
        /** @var City $city */
        $city = $array[$current % 50];
        $name = $city->getName();
        $key = intval(floor($current / 50 % 10));
        return $data[$key] . " " . $name;
    }

    public function buildingForRoom($array, $current)
    {
        return $array[($current - 1) % 500];
    }

    public function numberForRoom($current)
    {
        return $current / 500 + 100;
    }

    public function priceForRoom($roomIndex, $bedsIndex)
    {
        return $this->prices[$roomIndex % 500 + 1][$bedsIndex];
    }

    public function nameForCar($current)
    {
        $key = intval(floor($current / 50 % 20));
        return $this->cars[$key][1];
    }

    public function cityForCar($array, $current)
    {
        return $array[($current - 1) % 50];
    }

    public function imageForCar($current)
    {
        $key = intval(floor($current / 50 % 20));
        return $this->cars[$key][0];
    }

    private function extractCities()
    {
        $client = new Client();
        $crawler = $client->request('GET', self::CITY_URL);

        $names = $crawler
            ->filterXPath('//table[contains(@class, "wikitable")]//tbody/tr')
            ->reduce(function (Crawler $node, $i) {
                return ($i != 0 && $i <= 50);
            })
            ->each(function (Crawler $crawler) {
                return $crawler
                    ->filterXPath("//a")
                    ->first()
                    ->text();
            });
        $datas = [];
        foreach ($names as $eq => $name) {

            $image = $crawler
                ->filterXPath('//table[contains(@class, "wikitable")]//tbody/tr/td/a/img')
                ->eq($eq)
                ->attr("src");

            $image = $this->trimUrl($image);

            $datas[] = [$name, $image];
        }
        $this->cities = $datas;
    }


    private function trimUrl($url)
    {
        $arrayurl = explode("/", $url);
        array_splice($arrayurl, -1);
        array_splice($arrayurl, 5, 1);

        return implode("/", $arrayurl);
    }

    private function extractCars()
    {
        $client = new Client();
        $crawler = $client->request('GET', self::CAR_URL);

        $datas = $crawler
            ->filterXPath('//div[contains(@class, "mw-parser-output")]//ul/li/a')
            ->reduce(function (Crawler $node, $i) {
                return ($i != 10 && $i != 21 && ($i >= 5 && $i <= 28));
            })
            ->each(function (Crawler $crawler) {
                $url = "https://en.wikipedia.org/";
                $data = $crawler
                    ->extract(["href", "_text"])[0];

                $fullUrl = $url . $data[0];

                $client = new Client();
                $crawler = $client->request('GET', $fullUrl);

                $url = $crawler->filterXPath("//table[contains(@class, 'infobox')]//tbody/tr/td/a/img")
                    ->attr("src");
                $data[0] = $this->trimUrl($url);

                return $data;
            });
        $this->cars = $datas;
    }

}