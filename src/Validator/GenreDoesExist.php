<?php

namespace App\Validator;

#[\Attribute]
class GenreDoesExist
{
    public string $message = "Die Genre mit der ID {{ genreId }} existiert nicht.";
}