<?php

namespace App\Tests;

use App\DTO\CreateUpdateGenre;
use App\DTO\CreateUpdateMovie;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class MovieControllerTest extends WebTestCase
{

    private static $application;

    private static $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            "base_uri" => "http://localhost:8000/index_test.php"
        ]);

        $client = self::createClient();
        self::$application = new Application($client->getKernel());
        self::$application->setAutoExit(false);

        self::$application->run(new StringInput("doctrine:database:drop --force"));
        self::$application->run(new StringInput("doctrine:database:create"));
        self::$application->run(new StringInput("doctrine:schema:create"));
        self::$application->run(new StringInput("doctrine:fixtures:load"));
    }

    /**
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testPostMovie() {
        $dto = new CreateUpdateMovie();
        $dto->name = "Filmname";
        $dto->description = "Eine coole Beschreibung";
        $dto->agerest = 9;
        $dto->rating = 5;
        $dto->genre = 1;

        //Build request for post method
        $request = self::$client->request("POST", "api/movie",
            [
                "body" => json_encode($dto)
            ]
        );

        //get response for post method
        $response = json_decode($request->getBody());

        //asert methods for actual test code
        $this->assertTrue($request->getStatusCode() == 200);
        $this->assertTrue($response == "Film wurde erstellt.");
    }

    public function testPostGenre() {
        $dto = new CreateUpdateGenre();
        $dto->genre = "Genrenname";

        //Build request for post method
        $request = self::$client->request("POST", "api/genre",
            [
                "body" => json_encode($dto)
            ]
        );

        //get response for post method
        $response = json_decode($request->getBody());

        //asert methods for actual test code
        $this->assertTrue($request->getStatusCode() == 200);
        $this->assertTrue($response->Filmkategorie == "Genrenname");
    }

    public function testGetMovie() {
        $request = self::$client->request("GET", "api/movie");

        $this->assertTrue($request->getStatusCode() == 200);
    }

    public function testGetGenre() {
        $request = self::$client->request("GET", "api/genre");

        $this->assertTrue($request->getStatusCode() == 200);
    }

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}
