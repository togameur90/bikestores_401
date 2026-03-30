<?php
namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Store;
use Doctrine\ORM\EntityManager;

/**
 * Controller class to handle all operations related to Employees.
 */
class EmployeeController {
    /**
     * @var EntityManager The Doctrine EntityManager instance.
     */
    private $entityManager;
    
    /**
     * @var \Doctrine\ORM\EntityRepository The repository for Employee entities.
     */
    private $repository;

    /**
     * Constructor for EmployeeController.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager.
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Employee::class);
    }

    /**
     * Handles incoming HTTP requests and routes them to the appropriate action.
     *
     * @param string $method The HTTP method used.
     * @param string|int|null $action The requested action or resource ID.
     * @return array The result of the processed action.
     */
    public function handleRequest($method, $action) {
        if ($method === 'POST' && $action === 'login') return $this->login();
        
        if ($method === 'GET') {
            if ($action === 'getAllEmployees') return $this->getAllEmployees();
            if ($action === 'getEmployeeById') {
                $empId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                return $this->getEmployeeById($empId);
            }
            if ($action === 'getEmployeesByStore') {
                $storeId = isset($_GET['store_id']) ? (int)$_GET['store_id'] : 0;
                return $this->getEmployeesByStore($storeId);
            }
        }

        if ($method === 'POST' && $action === 'addEmployee') return $this->addEmployee();
        
        if ($method === 'PUT') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($action) ? (int)$action : null);
            if ($id) return $this->updateEmployee($id);
            http_response_code(400); return ['error' => 'ID manquant'];
        }

        if ($method === 'DELETE') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (is_numeric($action) ? (int)$action : null);
            if ($id) return $this->deleteEmployee($id);
            http_response_code(400); return ['error' => 'ID manquant'];
        }

        http_response_code(405);
        return ['error' => 'Action non autorisée.'];
    }

    /**
     * Formats an Employee entity into an array response.
     *
     * @param Employee $emp The employee entity.
     * @return array The formatted employee array.
     */
    private function formatEmployee(Employee $emp) {
        return [
            'employee_id'    => $emp->getEmployeeId(),
            'employee_name'  => $emp->getEmployeeName(),
            'employee_email' => $emp->getEmployeeEmail(),
            'employee_role'  => $emp->getEmployeeRole(),
            'store_id'       => $emp->getStore() ? $emp->getStore()->getStoreId() : null,
            'store_name'     => $emp->getStore() ? $emp->getStore()->getStoreName() : null
        ];
    }

    /**
     * Retrieves all employees from the database.
     *
     * @return array A list of formatted employee objects.
     */
    private function getAllEmployees() {
        $employees = $this->repository->findAll();
        $result = [];
        foreach ($employees as $emp) $result[] = $this->formatEmployee($emp);
        return $result;
    }

    /**
     * Retrieves employees belonging to a specific store.
     *
     * @param int $storeId The unique identifier of the store.
     * @return array A list of formatted employee objects or an error message.
     */
    private function getEmployeesByStore($storeId) {
        if (!$storeId) { 
            http_response_code(400); 
            return ['error' => 'Store ID manquant.']; 
        }
        $employees = $this->repository->findBy(['store' => $storeId]);
        $result = [];
        foreach ($employees as $emp) $result[] = $this->formatEmployee($emp);
        return $result;
    }

    /**
     * Adds a new employee to the database.
     *
     * @return array A success or error message.
     */
    private function addEmployee() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['employee_name']) || empty($data['employee_email']) || empty($data['store_id'])) {
            http_response_code(400); return ['error' => 'Données incomplètes.'];
        }

        $store = $this->entityManager->getRepository(Store::class)->find($data['store_id']);
        if (!$store) { 
            http_response_code(404); 
            return ['error' => 'Magasin introuvable.']; 
        }

        $emp = new Employee();
        $emp->setEmployeeName(trim($data['employee_name']));
        $emp->setEmployeeEmail(trim($data['employee_email']));
        $emp->setEmployeeRole($data['employee_role'] ?? 'employee');
        $emp->setStore($store);
        $emp->setEmployeePassword('password123'); // Default password

        $this->entityManager->persist($emp);
        $this->entityManager->flush();

        return ['success' => true, 'message' => 'Employé ajouté.'];
    }

    /**
     * Updates an existing employee. Handles role, email, name, store, and password changes.
     *
     * @param int $id The unique identifier of the employee.
     * @return array A success message and formatted employee data, or an error message.
     */
    private function updateEmployee($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $emp = $this->repository->find($id);

        if (!$emp) { 
            http_response_code(404); 
            return ['error' => 'Employé introuvable.']; 
        }

        if (isset($data['current_password'])) {
            if ($emp->getEmployeePassword() !== $data['current_password']) {
                http_response_code(401);
                return ['error' => 'Incorrect current password.'];
            }
            if (!empty($data['new_email'])) {
                $emp->setEmployeeEmail(trim($data['new_email']));
            }
            if (!empty($data['new_password'])) {
                $emp->setEmployeePassword($data['new_password']);
            }
        }

        if (!empty($data['employee_name'])) $emp->setEmployeeName(trim($data['employee_name']));
        if (!empty($data['employee_email'])) $emp->setEmployeeEmail(trim($data['employee_email']));
        if (!empty($data['employee_role'])) $emp->setEmployeeRole($data['employee_role']);
        
        if (!empty($data['store_id'])) {
            $store = $this->entityManager->getRepository(Store::class)->find($data['store_id']);
            if ($store) $emp->setStore($store);
        }

        $this->entityManager->flush();
        return ['success' => true, 'message' => 'Employé modifié.', 'user' => $this->formatEmployee($emp)];
    }

    /**
     * Deletes an employee from the database.
     *
     * @param int $id The unique identifier of the employee.
     * @return array A success message or an error message.
     */
    private function deleteEmployee($id) {
        $emp = $this->repository->find($id);
        if (!$emp) { 
            http_response_code(404); 
            return ['error' => 'Employé introuvable.']; 
        }

        try {
            $this->entityManager->remove($emp);
            $this->entityManager->flush();
            return ['success' => true, 'message' => 'Employé supprimé.'];
        } catch (\Exception $e) {
            http_response_code(409); 
            return ['error' => 'Impossible de supprimer cet employé.'];
        }
    }

    /**
     * Authenticates an employee based on email and password.
     *
     * @return array A success message with the user data, or an error message.
     */
    private function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400); 
            return ['error' => 'Veuillez fournir un email et un mot de passe.'];
        }

        $employee = $this->repository->findOneBy(['employee_email' => $data['email']]);
        if (!$employee || $employee->getEmployeePassword() !== $data['password']) {
            http_response_code(401); 
            return ['error' => 'Email ou mot de passe incorrect.'];
        }

        return ['success' => true, 'message' => 'Connexion réussie', 'user' => $this->formatEmployee($employee)];
    }

    /**
     * Retrieves an employee by their ID.
     *
     * @param int $id The unique identifier of the employee.
     * @return array The formatted employee object or an error message.
     */
    private function getEmployeeById($id) {
        if (!$id) { 
            http_response_code(400); 
            return ['error' => 'ID manquant.']; 
        }
        
        $emp = $this->repository->find($id);
        if (!$emp) {
            http_response_code(404); 
            return ['error' => 'Employé introuvable.'];
        }
        
        return $this->formatEmployee($emp);
    }
}