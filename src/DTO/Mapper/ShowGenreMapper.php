<?php

namespace App\DTO\Mapper;

use App\DTO\ShowGenre;

/**
 * Mapper for Genre.
 */
class ShowGenreMapper extends BaseMapper
{
    /**
     * @return ShowGenre
     */
    public function mapEntityToDTO(object $entity): object
    {
        $dto = new ShowGenre();
        $dto->genre = $entity->getGenre();

        return $dto;
    }
}
