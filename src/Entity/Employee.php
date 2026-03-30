<?php
// src/Entity/Employee.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents an employee in the Bike Stores application.
 *
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employee {
    
    /**
     * The unique identifier for the employee.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $employee_id;

    /**
     * The full name of the employee.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $employee_name;

    /**
     * The email address of the employee, used for login.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $employee_email;

    /**
     * The password of the employee.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $employee_password;

    /**
     * The role of the employee (e.g., employee, chief, it).
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $employee_role;

    /**
     * The store where the employee works.
     *
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id", nullable=true)
     * @var Store|null
     */
    private ?Store $store = null;

    /**
     * Returns a string representation of the employee.
     *
     * @return string A string containing the employee ID, name, and role.
     */
    public function __toString(): string {
        return "Employé {$this->employee_id} : {$this->employee_name} ({$this->employee_role})\n";
    }

    // --- GETTERS & SETTERS ---

    /**
     * Gets the unique identifier of the employee.
     *
     * @return int The employee ID.
     */
    public function getEmployeeId(): int { return $this->employee_id; }

    /**
     * Sets the full name of the employee.
     *
     * @param string $employee_name The name of the employee.
     * @return self Returns the current instance for method chaining.
     */
    public function setEmployeeName(string $employee_name): self { $this->employee_name = $employee_name; return $this; }
    
    /**
     * Gets the full name of the employee.
     *
     * @return string The name of the employee.
     */
    public function getEmployeeName(): string { return $this->employee_name; }

    /**
     * Sets the email address of the employee.
     *
     * @param string $employee_email The email address.
     * @return self Returns the current instance for method chaining.
     */
    public function setEmployeeEmail(string $employee_email): self { $this->employee_email = $employee_email; return $this; }
    
    /**
     * Gets the email address of the employee.
     *
     * @return string The email address.
     */
    public function getEmployeeEmail(): string { return $this->employee_email; }

    /**
     * Sets the password for the employee.
     *
     * @param string $employee_password The password string.
     * @return self Returns the current instance for method chaining.
     */
    public function setEmployeePassword(string $employee_password): self { $this->employee_password = $employee_password; return $this; }
    
    /**
     * Gets the password of the employee.
     *
     * @return string The employee password.
     */
    public function getEmployeePassword(): string { return $this->employee_password; }

    /**
     * Sets the role of the employee.
     *
     * @param string $employee_role The role of the employee.
     * @return self Returns the current instance for method chaining.
     */
    public function setEmployeeRole(string $employee_role): self { $this->employee_role = $employee_role; return $this; }
    
    /**
     * Gets the role of the employee.
     *
     * @return string The employee role.
     */
    public function getEmployeeRole(): string { return $this->employee_role; }

    /**
     * Sets the store where the employee works.
     *
     * @param Store|null $store The associated store entity.
     * @return self Returns the current instance for method chaining.
     */
    public function setStore(?Store $store): self { $this->store = $store; return $this; }
    
    /**
     * Gets the store where the employee works.
     *
     * @return Store|null The associated store entity, or null if not assigned.
     */
    public function getStore(): ?Store { return $this->store; }
}
?>