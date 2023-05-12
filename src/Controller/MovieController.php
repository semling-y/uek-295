<?php

namespace App\Controller;

use App\DTO\CreateUpdateMovie;
use App\DTO\FilterMovie;
use App\DTO\Mapper\ShowMovieMapper;
use App\DTO\ShowMovie;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *All Methods for Movie.
 */
#[Route('/api', name: 'api_')]
class MovieController extends AbstractController
{
    /**
     *Contructor for Movie.
     */
    public function __construct(private SerializerInterface $serializer, private MovieRepository $repository, private GenreRepository $genreRepository, private ValidatorInterface $validator, private ShowMovieMapper $mapper)
    {
    }

    #[\OpenApi\Attributes\Post(
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: new Model(
                    type: CreateUpdateMovie::class,
                    groups: (['create'])
                )
            )
        )
    )]
    /**
     * Post Method for Movie.
     *
     * @return JsonResponse
     */
    #[Post('/movie', name: 'app_data_create')]
    public function createMovie(Request $request)
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateMovie::class, 'json');
        $genre = $this->genreRepository->find($dto->genre);

        $errors = $this->validator->validate($dto, groups: ['create']);

        if ($errors->count() > 0) {
            $errorsStringArray = [];
            foreach ($errors as $error) {
                $errorsStringArray = $error->getMessage();
            }

            return $this->json($errorsStringArray, status: 400);
        }

        $entity = new Movie();

        $entity->setName($dto->name);
        $entity->setDescription($dto->description);
        $entity->setGenre($genre);
        $entity->setAgerest($dto->agerest);
        $entity->setRating($dto->rating);

        $this->repository->save($entity, true);

        return $this->json($entity->getName().' wurde auf ID: '.$entity->getId().' erstellt.');
    }

    #[\OpenApi\Attributes\Get(requestBody: new RequestBody(
        content: new JsonContent(
            ref: new Model(
                type: FilterMovie::class
            )
        )
    ))]
    #[\OpenApi\Attributes\Response(
        response: 200,
        description: 'Gibt alle Filme inklusive deren Genren zurück.',
        content: new JsonContent(
            type: 'array',
            items: new Items(
                ref: new Model(
                    type: ShowMovie::class
                )
            )
        )
    )]
    /**
     * Get Method for Movie.
     */
    #[Get('/movie', name: 'app_data_get')]
    public function getmovie(Request $request): Response
    {
        $dtoFilter = null;

        try {
            $dtoFilter = $this->serializer->deserialize(
                $request->getContent(),
                FilterMovie::class,
                'json'
            );
        } catch (\Exception $ex) {
            $dtoFilter = new FilterMovie();
        }

        $dtoAllMovie = $this->repository->filterAll($dtoFilter);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntitiesToDTOS($dtoAllMovie), 'json'
            )
        );
    }

    /**
     * Put Method for Movie.
     */
    #[Route('/movie/{id}', name: 'app_data_update', methods: ['PUT'])]
    public function updateMovie(Request $request, int $id): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateMovie::class, 'json');

        $movie = $this->repository->find($id);

        if (!$movie) {
            return $this->json('Dieser Film wurde nicht gefunden.');
        }

        $genre = $this->genreRepository->find($dto->genre);

        $movie->setName($dto->name);
        $movie->setDescription($dto->description);
        $movie->setGenre($genre);
        $movie->setAgerest($dto->agerest);
        $movie->setRating($dto->rating);

        $this->repository->save($movie, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntityToDTO($movie),
                'json'
            )
        );
    }

    /**
     * Delete Method for Movie.
     */
    #[Delete('/movie/{id}', name: 'app_data_delete')]
    public function deleteMovie(int $id): JsonResponse
    {
        $movie = $this->repository->find($id);

        if (!$movie) {
            return $this->json('Dieser Film wurde nicht gefunden.');
        }

        $this->repository->remove($movie, true);

        return $this->json('Film wurde gelöscht.');
    }
}
