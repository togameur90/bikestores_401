<?php
namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManager;

/**
 * Controller class to handle all operations related to Categories.
 */
class CategoryController {
    /**
     * @var EntityManager The Doctrine EntityManager instance.
     */
    private $entityManager;

    /**
     * Constructor for CategoryController.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager.
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Handles incoming HTTP requests and routes them to the appropriate action.
     *
     * @param string $method The HTTP method used.
     * @param string|int|null $action The requested action or resource ID.
     * @return array The result of the processed action or an error message.
     */
    public function handleRequest($method, $action) {
        if ($method === 'GET') {
            if (is_numeric($action)) {
                return $this->getCategoryById((int)$action);
            }
            return $this->getCategories();
        }
        if ($method === 'POST' && $action === 'addCategory') {
            return $this->addCategory();
        }
        if ($method === 'PUT') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($action) ? (int)$action : null);
            if ($id) return $this->updateCategory($id);
            http_response_code(400); 
            return ['error' => 'ID manquant pour la modification.'];
        }
        if ($method === 'DELETE') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($action) ? (int)$action : null);
            if ($id) return $this->deleteCategory($id);
            http_response_code(400); 
            return ['error' => 'ID manquant pour la suppression.'];
        }

        http_response_code(405);
        return ['error' => 'Méthode non autorisée pour les catégories.'];
    }

    /**
     * Retrieves all categories from the database.
     *
     * @return array A list of category arrays.
     */
    private function getCategories() {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'category_id' => $category->getCategoryId(), 
                'category_name' => $category->getCategoryName()
            ];
        }
        return $result;
    }

    /**
     * Retrieves a single category by its ID.
     *
     * @param int $id The unique identifier of the category.
     * @return array The category data or an error message if not found.
     */
    private function getCategoryById($id) {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        
        if ($category) {
            return [
                'category_id' => $category->getCategoryId(), 
                'category_name' => $category->getCategoryName()
            ];
        }
        
        http_response_code(404);
        return ['error' => 'Catégorie introuvable.'];
    }

    /**
     * Adds a new category.
     *
     * @return array A success message or an error message.
     */
    private function addCategory() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty(trim($data['category_name'] ?? ''))) {
            http_response_code(400); 
            return ['error' => 'Le nom est requis.'];
        }
        $category = new Category();
        $category->setCategoryName(trim($data['category_name']));
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return ['success' => true, 'message' => 'Catégorie ajoutée !'];
    }

    /**
     * Updates an existing category based on its ID.
     *
     * @param int $id The unique identifier of the category.
     * @return array A success message or an error message.
     */
    private function updateCategory($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        
        if (!$category) { 
            http_response_code(404); 
            return ['error' => 'Catégorie introuvable.']; 
        }
        
        if (!empty($data['category_name'])) {
            $category->setCategoryName(trim($data['category_name']));
            $this->entityManager->flush();
        }
        return ['success' => true, 'message' => 'Catégorie modifiée avec succès !'];
    }

    /**
     * Deletes a category from the database.
     *
     * @param int $id The unique identifier of the category.
     * @return array A success message or an error if constrained by foreign keys.
     */
    private function deleteCategory($id) {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        
        if (!$category) { 
            http_response_code(404); 
            return ['error' => 'Catégorie introuvable.']; 
        }
        
        try {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            return ['success' => true, 'message' => 'Catégorie supprimée avec succès.'];
        } catch (\Exception $e) {
            http_response_code(409);
            return ['error' => 'Impossible : des produits utilisent encore cette catégorie.'];
        }
    }
}