<?php
namespace App\Controller;

use App\Service\ZooManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZooController extends AbstractController
{
    public function __construct(private ZooManager $zooManager) {}

    #[Route('/', name: 'app_zoo', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('zoo/index.html.twig');
    }

    #[Route('/api/zoo/cells', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->zooManager->getZoo());
    }

    #[Route('/api/zoo/cell/clean/{id}', methods: ['GET'])]
    public function cleanCell(int $id): JsonResponse
    {
        try {
            $message = $this->zooManager->cleanCell($id);
            return $this->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'error' => 'Ошибка: ' . $e->getMessage(),
            ], 400);
        }

    }

    #[Route('/api/zoo/cell', methods: ['POST'])]
    public function createCell(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $type = $data['type'] ?? null;

        $cell = $this->zooManager->createCell($type);
        return $this->json(['id' => $cell->getId(), 'type' => $cell->getType()]);
    }



    #[Route('/api/zoo/animal', methods: ['POST'])]
    public function addAnimal(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cellId = (int) ($data['cellId'] ?? 0);
        $species = $data['species'] ?? '';

        try {
            $animal = $this->zooManager->addAnimal($cellId, $species);
            return $this->json([
                'success' => true,
                'id' => $animal->getId(),
                'species' => $animal->getSpecies()
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }

    }


    #[Route('/api/zoo/animal/action', methods: ['POST'])]
    public function performAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = (int) ($data['id'] ?? 0);
        $action = $data['action'] ?? '';

        try {
            $message = $this->zooManager->performAction($id,$action);
            return $this->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'error' => 'Ошибка: ' . $e->getMessage(),
            ], 400);
        }

    }



    #[Route('/api/zoo/animal/feed/{id}', methods: ['GET'])]
    public function feedAnimal(int $id): JsonResponse
    {
        try {
            $message = $this->zooManager->feedAnimal($id);
            return $this->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'error' => 'Ошибка: ' . $e->getMessage(),
            ], 400);
        }
    }


    #[Route('/api/zoo/animal/{id}', methods: ['DELETE'])]
    public function removeAnimal(int $id): JsonResponse
    {
        $this->zooManager->removeAnimal($id);
        return $this->json(['status' => 'ok']);
    }
}
