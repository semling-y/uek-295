<?php

namespace App\DTO;

use JMS\Serializer\Annotation\SerializedName;

class ShowGenre
{
    #[SerializedName("Filmkategorie")]
    public ?string $genre = null;
}