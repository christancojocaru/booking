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
    const URL = 'https://ro.wikipedia.org/wiki/Lista_ora%C8%99elor_din_Rom%C3%A2nia';

    /** @var EntityManagerInterface $em */
    private $em;
    /** @var object */
    private $crawler;
    /** @var array */
    private $datas;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        $client = new Client();
        $this->crawler = $client->request('GET', self::URL);

        $this->extractData();
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

    public function imageForCity($no)
    {
        return $this->datas[$no][1];
    }

    public function nameForCity($no)
    {
        return $this->datas[$no][0];
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
        return $array[$current % 500];
    }

    public function number($current)
    {
        return $current / 500 + 100;
    }

    private function extractData()
    {
        $names = $this->crawler
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

            $image = $this->crawler
                ->filterXPath('//table[contains(@class, "wikitable")]//tbody/tr/td/a/img')
                ->eq($eq)
                ->attr("src");
            $arrayurl = explode("/", $image);
            array_splice($arrayurl, -1);
            array_splice($arrayurl, 5, 1);

            $image = implode("/", $arrayurl);

            $datas[] = [$name, $image];
        }
        $this->datas = $datas;
    }

}