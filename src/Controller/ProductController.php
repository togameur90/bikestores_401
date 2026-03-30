<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Brand;
use App\Entity\Category;
use Doctrine\ORM\EntityManager;

/**
 * Controller class to handle all operations related to Products.
 */
class ProductController {
    /**
     * @var EntityManager The Doctrine EntityManager instance.
     */
    private $entityManager;
    
    /**
     * @var \Doctrine\ORM\EntityRepository The repository for Product entities.
     */
    private $repository;

    /**
     * Constructor for ProductController.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager.
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Product::class);
    }

    /**
     * Formats a Product entity into an array response.
     *
     * @param Product|null $product The Product entity to format.
     * @return array|null The formatted product data or null if the product is null.
     */
    private function formatProduct(?Product $product) {
        if ($product === null) return null;
        return [
            'product_id'    => $product->getProductId(),
            'product_name'  => $product->getProductName(),
            'price'         => (float) $product->getListPrice(),
            'list_price'    => (float) $product->getListPrice(),
            'model_year'    => $product->getModelYear(),
            'brand_id'      => $product->getBrand() ? $product->getBrand()->getBrandId() : null,
            'brand_name'    => $product->getBrand() ? $product->getBrand()->getBrandName() : null,
            'category_id'   => $product->getCategory() ? $product->getCategory()->getCategoryId() : null,
            'category_name' => $product->getCategory() ? $product->getCategory()->getCategoryName() : null,
        ];
    }

    /**
     * Handles incoming HTTP requests and routes them to the appropriate action.
     *
     * @param string $method The HTTP method used.
     * @param string|int|null $actionOrId The requested action or resource ID.
     * @return array The result of the processed action or an error response.
     */
    public function handleRequest($method, $actionOrId) {
        if ($method === 'GET') {
            if (is_numeric($actionOrId)) {
                $product = $this->repository->find((int)$actionOrId);
                if ($product) {
                    return $this->formatProduct($product);
                } else {
                    http_response_code(404);
                    return ['error' => 'Produit introuvable'];
                }
            } else {
                $products = $this->repository->findAll();
                return array_map([$this, 'formatProduct'], $products);
            }
        }

        if ($method === 'POST' && $actionOrId === 'addProduct') {
            return $this->addProduct();
        }

        if ($method === 'PUT') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($actionOrId) ? (int)$actionOrId : null);
            if ($id) {
                return $this->updateProduct($id);
            }
            http_response_code(400);
            return ['error' => 'ID manquant pour la modification.'];
        }

        if ($method === 'DELETE') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($actionOrId) ? (int)$actionOrId : null);
            if ($id) {
                return $this->deleteProduct($id);
            }
            http_response_code(400);
            return ['error' => 'ID manquant pour la suppression.'];
        }

        http_response_code(405);
        return ['error' => 'Méthode HTTP non autorisée pour la ressource products.'];
    }

    /**
     * Adds a new product to the database.
     *
     * @return array A success message or an error message.
     */
    private function addProduct() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['product_name']) || empty($data['list_price'])) {
            http_response_code(400);
            return ['error' => 'Le nom du produit et le prix sont obligatoires.'];
        }

        $product = new Product();
        $product->setProductName($data['product_name']);
        $product->setListPrice((float)$data['list_price']);
        
        if (!empty($data['model_year'])) {
            $product->setModelYear((int)$data['model_year']);
        }

        if (!empty($data['brand_id'])) {
            $brand = $this->entityManager->getRepository(Brand::class)->find($data['brand_id']);
            if ($brand) $product->setBrand($brand);
        }

        if (!empty($data['category_id'])) {
            $category = $this->entityManager->getRepository(Category::class)->find($data['category_id']);
            if ($category) $product->setCategory($category);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return ['success' => true, 'message' => 'Produit ajouté avec succès.'];
    }

    /**
     * Updates an existing product in the database.
     *
     * @param int $id The unique identifier of the product.
     * @return array A success message or an error message.
     */
    private function updateProduct($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $product = $this->repository->find($id);

        if (!$product) {
            http_response_code(404);
            return ['error' => 'Produit introuvable.'];
        }

        if (!empty($data['product_name'])) {
            $product->setProductName($data['product_name']);
        }
        if (isset($data['list_price'])) {
            $product->setListPrice((float)$data['list_price']);
        }
        if (!empty($data['model_year'])) {
            $product->setModelYear((int)$data['model_year']);
        }

        if (!empty($data['brand_id'])) {
            $brand = $this->entityManager->getRepository(Brand::class)->find($data['brand_id']);
            if ($brand) $product->setBrand($brand);
        }

        if (!empty($data['category_id'])) {
            $category = $this->entityManager->getRepository(Category::class)->find($data['category_id']);
            if ($category) $product->setCategory($category);
        }

        $this->entityManager->flush();

        return ['success' => true, 'message' => 'Produit mis à jour avec succès.'];
    }
    
    /**
     * Deletes a product from the database.
     *
     * @param int $id The unique identifier of the product.
     * @return array A success message or an error if constraints prevent deletion.
     */
    private function deleteProduct($id) {
        $product = $this->repository->find($id);

        if (!$product) {
            http_response_code(404);
            return ['error' => 'Produit introuvable.'];
        }

        try {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
            return ['success' => true, 'message' => 'Produit supprimé avec succès.'];
        } catch (\Exception $e) {
            http_response_code(409);
            return ['error' => 'Impossible de supprimer ce vélo car il est lié à des stocks ou des commandes existantes.'];
        }
    }
}