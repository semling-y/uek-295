<?php

namespace App\Tests;

use App\DTO\CreateUpdateMovie;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class MovieControllerTest extends TestCase
{

    private static $client;

    /**
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            "base_uri" => "http://localhost:8000"
        ]);
    }

    /**
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testPost() {
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

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}
