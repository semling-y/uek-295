<?php

namespace App\DTO;

use JMS\Serializer\Annotation\Groups;

class CreateUpdateGenre
{
    #[Groups(["create", "update"])]
    public ?string $genre = null;
}