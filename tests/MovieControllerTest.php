<?php

namespace App\Tests;

use App\DTO\CreateUpdateGenre;
use App\DTO\CreateUpdateMovie;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class MovieControllerTest extends WebTestCase
{
    private static $application;

    private static $client;

    private static $token;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            'base_uri' => 'http://localhost:8000/index_test.php/',
        ]);

        $client = self::createClient();
        self::$application = new Application($client->getKernel());
        self::$application->setAutoExit(false);

        self::$application->run(new StringInput('doctrine:database:drop --force'));
        self::$application->run(new StringInput('doctrine:database:create --quiet'));
        self::$application->run(new StringInput('doctrine:schema:create'));
        self::$application->run(new StringInput('doctrine:fixtures:load'));
    }

    public function setUp(): void
    {
        $requestLogin = self::$client->request('POST', 'api/login_check',
            [
                'body' => json_encode([
                    'username' => 'Admin',
                    'password' => 'admin',
                ]),
            ]);

        self::$token = json_decode($requestLogin->getBody())->token;
    }

    /**
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testPostMovie()
    {
        $dto = new CreateUpdateMovie();
        $dto->name = 'Filmname';
        $dto->description = 'Eine coole Beschreibung';
        $dto->agerest = 9;
        $dto->rating = 5;
        $dto->genre = 1;

        // Build request for post method
        $request = self::$client->request('POST', 'api/movie',
            [
                'body' => json_encode($dto),
                'headers' => [
                    'Authorization' => 'Bearer '.self::$token,
                ],
            ]
        );

        // get response for post method
        $response = json_decode($request->getBody());

        // asert methods for actual test code
        $this->assertTrue(200 == $request->getStatusCode());
    }

    public function testPostGenre()
    {
        $dto = new CreateUpdateGenre();
        $dto->genre = 'Genrenname';

        // Build request for post method
        $request = self::$client->request('POST', 'api/genre',
            [
                'body' => json_encode($dto),
                'headers' => [
                    'Authorization' => 'Bearer '.self::$token,
                ],
            ]
        );

        // get response for post method
        $response = json_decode($request->getBody());

        // assert methods for actual test code
        $this->assertTrue(200 == $request->getStatusCode());
        $this->assertTrue('Genrenname' == $response->Filmkategorie);
    }

    public function testGetMovie()
    {
        // Build request for post method
        $request = self::$client->request('GET', 'api/movie',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.self::$token,
                ],
            ]
        );

        $this->assertTrue(200 == $request->getStatusCode());
    }

    public function testGetGenre()
    {
        $request = self::$client->request('GET', 'api/genre',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.self::$token,
                ],
            ]);

        $this->assertTrue(200 == $request->getStatusCode());
    }

    public function testPutMovie(): void
    {
        $updatedDto = new CreateUpdateMovie();
        $updatedDto->name = 'Neuer Filmname';
        $updatedDto->description = 'Coolere Beschreibung';
        $updatedDto->agerest = 12;
        $updatedDto->rating = 4;
        $updatedDto->genre = 2;

        $putRequest = self::$client->request('PUT', 'api/movie/1', [
            'body' => json_encode($updatedDto),
            'headers' => [
                'Authorization' => 'Bearer '.self::$token,
            ],
        ]);

        // assert that the response code is 200 and the movie was updated
        $this->assertTrue(200 == $putRequest->getStatusCode());
    }

    public function testDeleteMovie(): void
    {
        // delete the movie
        $deleteRequest = self::$client->request('DELETE', 'api/movie/2',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.self::$token,
                ],
            ]);

        // assert that the response code is 200 and the movie was deleted
        $this->assertTrue(200 == $deleteRequest->getStatusCode());
    }

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}
