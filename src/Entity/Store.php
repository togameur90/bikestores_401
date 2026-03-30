<?php
// src/Entity/Store.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a physical store location in the Bike Stores application.
 *
 * @ORM\Entity
 * @ORM\Table(name="stores")
 */
class Store {
    
    /**
     * The unique identifier for the store.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $store_id;

    /**
     * The name of the store.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $store_name;

    /**
     * The contact phone number of the store.
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     * @var string|null
     */
    private ?string $phone = null;

    /**
     * The contact email address of the store.
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private ?string $email = null;

    /**
     * The street address of the store.
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private ?string $street = null;

    /**
     * The city where the store is located.
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private ?string $city = null;

    /**
     * The state or province where the store is located.
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     * @var string|null
     */
    private ?string $state = null;

    /**
     * The zip or postal code of the store.
     *
     * @ORM\Column(type="string", length=5, nullable=true)
     * @var string|null
     */
    private ?string $zip_code = null;

    /**
     * Returns a string representation of the store.
     *
     * @return string A string containing the store ID, name, and city.
     */
    public function __toString(): string {
        return "Magasin {$this->store_id} : {$this->store_name} ({$this->city})\n";
    }

    // --- GETTERS & SETTERS ---

    /**
     * Gets the unique identifier of the store.
     *
     * @return int The store ID.
     */
    public function getStoreId(): int { return $this->store_id; }

    /**
     * Sets the name of the store.
     *
     * @param string $store_name The name of the store.
     * @return self Returns the current instance for method chaining.
     */
    public function setStoreName(string $store_name): self { $this->store_name = $store_name; return $this; }
    
    /**
     * Gets the name of the store.
     *
     * @return string The name of the store.
     */
    public function getStoreName(): string { return $this->store_name; }

    /**
     * Sets the phone number of the store.
     *
     * @param string|null $phone The phone number.
     * @return self Returns the current instance for method chaining.
     */
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }
    
    /**
     * Gets the phone number of the store.
     *
     * @return string|null The phone number.
     */
    public function getPhone(): ?string { return $this->phone; }

    /**
     * Sets the email address of the store.
     *
     * @param string|null $email The email address.
     * @return self Returns the current instance for method chaining.
     */
    public function setEmail(?string $email): self { $this->email = $email; return $this; }
    
    /**
     * Gets the email address of the store.
     *
     * @return string|null The email address.
     */
    public function getEmail(): ?string { return $this->email; }

    /**
     * Sets the street address of the store.
     *
     * @param string|null $street The street address.
     * @return self Returns the current instance for method chaining.
     */
    public function setStreet(?string $street): self { $this->street = $street; return $this; }
    
    /**
     * Gets the street address of the store.
     *
     * @return string|null The street address.
     */
    public function getStreet(): ?string { return $this->street; }

    /**
     * Sets the city of the store location.
     *
     * @param string|null $city The city name.
     * @return self Returns the current instance for method chaining.
     */
    public function setCity(?string $city): self { $this->city = $city; return $this; }
    
    /**
     * Gets the city of the store location.
     *
     * @return string|null The city name.
     */
    public function getCity(): ?string { return $this->city; }

    /**
     * Sets the state or province of the store location.
     *
     * @param string|null $state The state abbreviation or name.
     * @return self Returns the current instance for method chaining.
     */
    public function setState(?string $state): self { $this->state = $state; return $this; }
    
    /**
     * Gets the state or province of the store location.
     *
     * @return string|null The state abbreviation or name.
     */
    public function getState(): ?string { return $this->state; }

    /**
     * Sets the zip code of the store location.
     *
     * @param string|null $zip_code The zip code.
     * @return self Returns the current instance for method chaining.
     */
    public function setZipCode(?string $zip_code): self { $this->zip_code = $zip_code; return $this; }
    
    /**
     * Gets the zip code of the store location.
     *
     * @return string|null The zip code.
     */
    public function getZipCode(): ?string { return $this->zip_code; }
}
?>