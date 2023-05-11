<?php

namespace App\DTO;

use JMS\Serializer\Annotation\SerializedName;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;

class ShowGenre
{
    #[SerializedName("Filmkategorie")]
    public ?string $genre = null;

    #[Property(
        "movie",
        type: "array",
        items: new Items(
            ref: new Model(
                type: ShowMovie::class
            )
        )
    )]
    public $movie = [];

}