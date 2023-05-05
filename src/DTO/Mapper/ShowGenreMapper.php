<?php

namespace App\DTO\Mapper;

use App\DTO\ShowGenre;

class ShowGenreMapper extends BaseMapper
{

    public function mapEntityToDTO(object $entity) : object
    {
        $dto = new ShowGenre();
        $dto->genre = $entity->getGenre();

        return $dto;
    }

}