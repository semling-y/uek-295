<?php

namespace App\Controller;

use App\DTO\CreateUpdateGenre;
use App\DTO\Mapper\BaseMapper;
use App\DTO\Mapper\ShowGenreMapper;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: "api_")]
class GenreController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private  GenreRepository $repository, private ShowGenreMapper $mapper){

    }

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


    #[Post('/genre', name: 'app_genre_create')]
    public function createGenre(Request $request, GenreRepository $repository): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUpdateGenre::class, "json");

        $entity = new Genre();
        $entity->setGenre($dto->genre);

        $this->repository->save($entity, true);

        return (new JsonResponse())->setContent(
            $this->serializer->serialize(
                $this->mapper->mapEntityToDTO($entity), "json")
        );
    }

    #[Delete('/genre', name: 'app_genre_delete')]
    public function deleteGenre(): Response
    {
        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
        ]);
    }
}
