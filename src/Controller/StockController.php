<?php
namespace App\Controller;

use App\Entity\Stock;
use App\Entity\Store;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;

/**
 * Controller class to handle all operations related to Stocks.
 */
class StockController {
    /**
     * @var EntityManager The Doctrine EntityManager instance.
     */
    private $entityManager;
    
    /**
     * @var \Doctrine\ORM\EntityRepository The repository for Stock entities.
     */
    private $repository;

    /**
     * Constructor for StockController.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager.
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Stock::class);
    }

    /**
     * Handles incoming HTTP requests and routes them to the appropriate action.
     *
     * @param string $method The HTTP method used.
     * @param string|int|null $action The requested action.
     * @return array The result of the processed action or an error response.
     */
    public function handleRequest($method, $action) {
        if ($method === 'GET') {
            if ($action === 'getStocksByStore' && isset($_GET['store_id'])) {
                return $this->getStocksByStore((int) $_GET['store_id']);
            }
            if (is_numeric($action)) {
                return $this->getStocksByStore((int) $action);
            }
            if ($action === 'getStocksByStore') {
                http_response_code(400);
                return ['error' => 'store_id manquant.'];
            }
        }

        if ($method === 'PUT' && $action === 'updateStock') {
            return $this->updateStock();
        }

        if ($method === 'DELETE' && $action === 'deleteStock') {
            if (isset($_GET['store_id']) && isset($_GET['product_id'])) {
                return $this->deleteStock((int) $_GET['store_id'], (int) $_GET['product_id']);
            }
            http_response_code(400);
            return ['error' => 'store_id ou product_id manquant.'];
        }

        if ($method === 'POST' && $action === 'addStock') {
            return $this->addStock();
        }

        http_response_code(405);
        return ['error' => 'Action non autorisée pour les stocks.'];
    }

    /**
     * Adds an existing product to a store's stock.
     *
     * @return array A success message or an error message.
     */
    private function addStock() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['store_id']) || !isset($data['product_id']) || !isset($data['quantity'])) {
            http_response_code(400);
            return ['error' => 'Données incomplètes. store_id, product_id et quantity requis.'];
        }

        $existingStock = $this->repository->findOneBy([
            'store' => $data['store_id'],
            'product' => $data['product_id']
        ]);

        if ($existingStock) {
            http_response_code(409);
            return ['error' => 'Ce produit est déjà dans le stock de ce magasin. Utilisez la modification.'];
        }

        $store = $this->entityManager->getRepository(\App\Entity\Store::class)->find($data['store_id']);
        $product = $this->entityManager->getRepository(\App\Entity\Product::class)->find($data['product_id']);

        if (!$store || !$product) {
            http_response_code(404);
            return ['error' => 'Magasin ou Produit introuvable.'];
        }

        $stock = new Stock();
        $stock->setStore($store);
        $stock->setProduct($product);
        $stock->setQuantity((int) $data['quantity']);

        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        return ['success' => true, 'message' => 'Stock ajouté avec succès.'];
    }

    /**
     * Retrieves all stocks for a specific store.
     *
     * @param int $storeId The unique identifier of the store.
     * @return array A list of formatted stock objects.
     */
    private function getStocksByStore(int $storeId) {
        $stocks = $this->repository->findBy(['store' => $storeId]);
        $result = [];

        foreach ($stocks as $stock) {
            $result[] = [
                'store_id'     => $stock->getStore()->getStoreId(),
                'product_id'   => $stock->getProduct()->getProductId(),
                'product_name' => $stock->getProduct()->getProductName(),
                'quantity'     => $stock->getQuantity()
            ];
        }

        return $result;
    }

    /**
     * Updates the quantity of a specific product stock in a given store.
     *
     * @return array A success message or an error message.
     */
    private function updateStock() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['store_id']) || !isset($data['product_id']) || !isset($data['quantity'])) {
            http_response_code(400);
            return ['error' => 'Données incomplètes.'];
        }

        $stock = $this->repository->findOneBy([
            'store' => $data['store_id'],
            'product' => $data['product_id']
        ]);

        if (!$stock) {
            http_response_code(404);
            return ['error' => 'Stock introuvable pour ce magasin.'];
        }

        $stock->setQuantity((int) $data['quantity']);
        $this->entityManager->flush();

        return ['success' => true, 'message' => 'Stock mis à jour.'];
    }

    /**
     * Deletes a stock entry (product) from a store.
     *
     * @param int $storeId The unique identifier of the store.
     * @param int $productId The unique identifier of the product.
     * @return array A success or error message.
     */
    private function deleteStock(int $storeId, int $productId) {
        $stock = $this->repository->findOneBy([
            'store' => $storeId,
            'product' => $productId
        ]);

        if (!$stock) {
            http_response_code(404);
            return ['error' => 'Stock introuvable.'];
        }

        $this->entityManager->remove($stock);
        $this->entityManager->flush();

        return ['success' => true, 'message' => 'Stock supprimé.'];
    }
}