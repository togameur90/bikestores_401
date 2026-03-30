<?php
namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManager;

/**
 * Controller class to handle all operations related to Brands.
 */
class BrandController {
    /**
     * @var EntityManager The Doctrine EntityManager instance.
     */
    private $entityManager;

    /**
     * Constructor for BrandController.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager.
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Handles incoming HTTP requests and routes them to the appropriate action.
     *
     * @param string $method The HTTP method used (GET, POST, PUT, DELETE).
     * @param string|int|null $action The requested action or resource ID.
     * @return array The result of the processed action or an error message.
     */
    public function handleRequest($method, $action) {
        if ($method === 'GET') {
            if (is_numeric($action)) {
                return $this->getBrandById((int)$action);
            }
            return $this->getBrands();
        }
        if ($method === 'POST' && $action === 'addBrand') return $this->addBrand();
        
        if ($method === 'PUT') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($action) ? (int)$action : null);
            if ($id) return $this->updateBrand($id);
            http_response_code(400); 
            return ['error' => 'ID manquant'];
        }
        
        if ($method === 'DELETE') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($action) ? (int)$action : null);
            if ($id) return $this->deleteBrand($id);
            http_response_code(400); 
            return ['error' => 'ID manquant'];
        }

        http_response_code(405);
        return ['error' => 'Méthode non autorisée.'];
    }

    /**
     * Retrieves all brands from the database.
     *
     * @return array A list of brand arrays containing brand_id and brand_name.
     */
    private function getBrands() {
        $brands = $this->entityManager->getRepository(Brand::class)->findAll();
        $result = [];
        foreach ($brands as $brand) {
            $result[] = ['brand_id' => $brand->getBrandId(), 'brand_name' => $brand->getBrandName()];
        }
        return $result;
    }

    /**
     * Retrieves a single brand by its ID.
     *
     * @param int $id The unique identifier of the brand.
     * @return array The brand data or an error message if not found.
     */
    private function getBrandById($id) {
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        if ($brand) {
            return ['brand_id' => $brand->getBrandId(), 'brand_name' => $brand->getBrandName()];
        }
        http_response_code(404);
        return ['error' => 'Marque introuvable.'];
    }

    /**
     * Adds a new brand using JSON payload from the request body.
     *
     * @return array A success message or an error if validation fails.
     */
    private function addBrand() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty(trim($data['brand_name'] ?? ''))) {
            http_response_code(400); 
            return ['error' => 'Nom requis.'];
        }
        $brand = new Brand();
        $brand->setBrandName(trim($data['brand_name']));
        $this->entityManager->persist($brand);
        $this->entityManager->flush();
        return ['success' => true, 'message' => 'Marque ajoutée !'];
    }

    /**
     * Updates an existing brand based on the provided ID and request body.
     *
     * @param int $id The unique identifier of the brand to update.
     * @return array A success message or an error if not found.
     */
    private function updateBrand($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        if (!$brand) { 
            http_response_code(404); 
            return ['error' => 'Marque introuvable.']; 
        }
        
        if (!empty($data['brand_name'])) {
            $brand->setBrandName(trim($data['brand_name']));
            $this->entityManager->flush();
        }
        return ['success' => true, 'message' => 'Marque modifiée !'];
    }

    /**
     * Deletes a brand from the database.
     *
     * @param int $id The unique identifier of the brand to delete.
     * @return array A success message or an error if constraints prevent deletion.
     */
    private function deleteBrand($id) {
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        if (!$brand) { 
            http_response_code(404); 
            return ['error' => 'Marque introuvable.']; 
        }
        
        try {
            $this->entityManager->remove($brand);
            $this->entityManager->flush();
            return ['success' => true, 'message' => 'Marque supprimée.'];
        } catch (\Exception $e) {
            http_response_code(409);
            return ['error' => 'Impossible : des produits sont encore liés à cette marque.'];
        }
    }
}