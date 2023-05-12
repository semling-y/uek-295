<?php

namespace App\Controller;

use App\DTO\CreateUpdateGenre;
use App\DTO\Mapper\ShowGenreMapper;
use App\DTO\ShowGenre;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *All Methods for Genre.
 */
#[Route('/api', name: 'api_')]
class GenreController extends AbstractController
{
    /**
     * Constructor for Genre.
     */
    public function __construct(private SerializerInterface $serializer, private GenreRepository $repository, private ShowGenreMapper $mapper)
    {
    }

    #[\OpenApi\Attributes\Response(
        response: 200,
        description: 'Gibt alle Filme inklusive deren Genren zurück.',
        content: new JsonContent(
            type: 'array',
            items: new Items(
                ref: new \Nelmio\ApiDocBundle\Annotation\Model(
                    type: ShowGenre::class
                )
            )
        )
    )]
    /**
     * Get Method for Genre.
     */
    #[Get('/genre', name: 'app_genre_get')]
    public function getGenre(): JsonResponse
    {
        $genres = $this->repository->findAll();

        $data = $this->serializer->serialize(
            $this->mapper->mapEntitiesToDTOS($genres),
            'json'
        );

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[\OpenApi\Attributes\Post(
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: new Model(
                    type: CreateUpdateGenre::class,
                    groups: (['create'])
                )
            )
        )
    )]
    /**
     * Post Method for Genre.
     */
    #[Post('/genre', name: 'app_genre_create')]
    public function createGenre(Request $request, GenreRepository $repository): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateGenre::class, 'json');

        $entity = new Genre();
        $entity->setGenre($dto->genre);

        $this->repository->save($entity, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntityToDTO($entity), 'json')
        );
    }

    /**
     * Put Method for Genre.
     */
    #[Route('/genre/{id}', name: 'app_genre_update', methods: ['PUT'])]
    public function updateGenre(Request $request, int $id): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateGenre::class, 'json');

        $genre = $this->repository->find($id);

        if (!$genre) {
            return $this->json('Diese Genre wurde nicht gefunden.');
        }

        $genre->setGenre($dto->genre);

        $this->repository->save($genre, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntityToDTO($genre),
                'json'
            )
        );
    }

    /**
     * Delete Method for Genre.
     */
    #[Delete('/genre/{id}', name: 'app_genre_delete')]
    public function deleteGenre(int $id): JsonResponse
    {
        $genre = $this->repository->find($id);

        if (!$genre) {
            return $this->json('Diese Genre wurde nicht gefunden.');
        }

        $this->repository->remove($genre, true);

        return $this->json('Genre wurde gelöscht.');
    }
}
