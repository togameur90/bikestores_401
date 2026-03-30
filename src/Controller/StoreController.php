<?php
namespace App\Controller;

use App\Entity\Store;
use Doctrine\ORM\EntityManager;

/**
 * Controller class to handle all operations related to Stores.
 */
class StoreController {
    /**
     * @var EntityManager The Doctrine EntityManager instance.
     */
    private $entityManager;
    
    /**
     * @var \Doctrine\ORM\EntityRepository The repository for Store entities.
     */
    private $repository;

    /**
     * Constructor for StoreController.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager.
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Store::class);
    }

    /**
     * Formats a Store entity into an array response.
     *
     * @param Store|null $store The Store entity to format.
     * @return array|null The formatted store data or null if the input is null.
     */
    private function formatStore(?Store $store) {
        if ($store === null) return null;
        return [
            'store_id'   => $store->getStoreId(),
            'store_name' => $store->getStoreName(),
            'phone'      => $store->getPhone(),
            'email'      => $store->getEmail(),
            'street'     => $store->getStreet(),
            'city'       => $store->getCity(),
            'state'      => $store->getState(),
            'zip_code'   => $store->getZipCode()
        ];
    }

    /**
     * Handles incoming HTTP requests and routes them to the appropriate action.
     *
     * @param string $method The HTTP method used.
     * @param string|int|null $id The requested store ID, if applicable.
     * @return array The result of the processed action or an error message.
     */
    public function handleRequest($method, $id) {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $store = $this->repository->find($id);
                    if ($store) {
                        return $this->formatStore($store);
                    } else {
                        http_response_code(404);
                        return ['error' => 'Magasin introuvable'];
                    }
                } else {
                    $stores = $this->repository->findAll();
                    return array_map([$this, 'formatStore'], $stores);
                }
                
            case 'DELETE':
                if ($id) {
                    $store = $this->repository->find($id);
                    if ($store) {
                        try {
                            $this->entityManager->remove($store);
                            $this->entityManager->flush();
                            return ['success' => true, 'message' => 'Magasin supprimé avec succès.'];
                        } catch (\Exception $e) {
                            http_response_code(409);
                            return ['error' => 'Impossible : des employés ou stocks sont liés à ce magasin.'];
                        }
                    } else {
                        http_response_code(404);
                        return ['error' => 'Magasin introuvable.'];
                    }
                } else {
                    http_response_code(400);
                    return ['error' => 'ID manquant pour la suppression.'];
                }
                
            case 'PUT':
                if ($id) {
                    $store = $this->repository->find($id);
                    if ($store) {
                        $data = json_decode(file_get_contents('php://input'), true);
                        if (isset($data['store_name'])) $store->setStoreName(trim($data['store_name']));
                        if (isset($data['phone']))      $store->setPhone(trim($data['phone']));
                        if (isset($data['email']))      $store->setEmail(trim($data['email']));
                        if (isset($data['street']))     $store->setStreet(trim($data['street']));
                        if (isset($data['city']))       $store->setCity(trim($data['city']));
                        if (isset($data['state']))      $store->setState(trim($data['state']));
                        if (isset($data['zip_code']))   $store->setZipCode(trim($data['zip_code']));
                        $this->entityManager->flush();
                        return ['success' => true, 'message' => 'Magasin mis à jour avec succès.', 'store' => $this->formatStore($store)];
                    } else {
                        http_response_code(404);
                        return ['error' => 'Magasin introuvable.'];
                    }
                } else {
                    http_response_code(400);
                    return ['error' => 'ID manquant pour la mise à jour.'];
                }

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                if (empty(trim($data['store_name'] ?? ''))) {
                    http_response_code(400);
                    return ['error' => 'Le nom du magasin est requis.'];
                }
                $store = new Store();
                $store->setStoreName(trim($data['store_name']));
                if (!empty($data['phone']))    $store->setPhone(trim($data['phone']));
                if (!empty($data['email']))    $store->setEmail(trim($data['email']));
                if (!empty($data['street']))   $store->setStreet(trim($data['street']));
                if (!empty($data['city']))     $store->setCity(trim($data['city']));
                if (!empty($data['state']))    $store->setState(trim($data['state']));
                if (!empty($data['zip_code'])) $store->setZipCode(trim($data['zip_code']));
                $this->entityManager->persist($store);
                $this->entityManager->flush();
                return ['success' => true, 'message' => 'Magasin ajouté avec succès.', 'store' => $this->formatStore($store)];
                
            default:
                http_response_code(405);
                return ['error' => 'Méthode HTTP non autorisée pour la ressource stores.'];
        }
    }
}
