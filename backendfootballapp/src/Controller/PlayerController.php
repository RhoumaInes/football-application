<?php

namespace App\Controller;

use App\Entity\Player;
use App\Enum\Position;
use App\Repository\PlayerRepository;
use App\Service\PlayerImporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/players')]
final class PlayerController extends AbstractController{
    #[Route('' , methods: ['GET'], name: 'app_players_list')]
    public function getPlayers(PlayerRepository $playerRepository): JsonResponse
    {
        $players = $playerRepository->findAll();
        return $this->json($players, Response::HTTP_OK, [], ['groups' => 'player:read']);
    }

    #[Route('/{id}', methods: ['GET'], name: 'app_show_player')]
    public function getPlayer(int $id, PlayerRepository $playerRepository): JsonResponse
    {
        $player = $playerRepository->find($id);

        if (!$player) {
            throw new NotFoundHttpException('Player not found');
        }

        return $this->json($player, Response::HTTP_OK, [], ['groups' => 'player:read']);
    }


    #[Route('', methods: ['POST'], name: 'app_create_player')]
    public function createPlayer(Request $request , EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $player = new Player();
        $player->setFirstName($data['firstName']);
        $player->setLastName($data['lastName']);
        $player->setPosition(Position::from($data['position']));
        $player->setTeam($data['team']);
        $player->setAge($data['age']);

        $errors = $validator->validate($player);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($player);
        $entityManager->flush();

        return $this->json($player, Response::HTTP_CREATED, [], ['groups' => 'player:read']);
    }

    #[Route('/{id}', methods: ['PUT'], name: 'app_update_player')]
    public function updatePlayer(Request $request, int $id, PlayerRepository $playerRepository , ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $player = $playerRepository->find($id);

        if (!$player) {
            throw new NotFoundHttpException('Player not found');
        }

        $player->setFirstName($data['firstName']);
        $player->setLastName($data['lastName']);
        $player->setPosition(Position::from($data['position']));
        $player->setTeam($data['team']);
        $player->setAge($data['age']);

        $errors = $validator->validate($player);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json($player, Response::HTTP_OK, [], ['groups' => 'player:read']);
    }

    #[Route('/{id}', methods: ['DELETE'], name: 'app_delete_player')]
    public function deletePlayer(int $id, PlayerRepository $playerRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $player = $playerRepository->find($id);

        if (!$player) {
            throw new NotFoundHttpException('Player not found');
        }

        $entityManager->remove($player);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/import', methods: ['POST'], name: 'app_import_players')]
    public function importPlayers(Request $request, PlayerImporter $playerImporter): JsonResponse
    {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('file');

        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $playerImporter->importFromXlsx($file);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['message' => 'Import successful'], Response::HTTP_OK);
    }
}
