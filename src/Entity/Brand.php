<?php
// src/Entity/Brand.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a brand in the Bike Stores application.
 *
 * @ORM\Entity
 * @ORM\Table(name="brands")
 */
class Brand {
    
    /**
     * The unique identifier for the brand.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $brand_id;

    /**
     * The name of the brand.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $brand_name;

    /**
     * Returns a string representation of the brand.
     *
     * @return string A string containing the brand ID and name.
     */
    public function __toString(): string {
        return "Marque {$this->brand_id} : {$this->brand_name}\n";
    }

    /**
     * Gets the unique identifier of the brand.
     *
     * @return int The brand ID.
     */
    public function getBrandId(): int {
        return $this->brand_id;
    }

    /**
     * Sets the name of the brand.
     *
     * @param string $brand_name The name of the brand.
     * @return self Returns the current instance for method chaining.
     */
    public function setBrandName(string $brand_name): self {
        $this->brand_name = $brand_name;
        return $this;
    }

    /**
     * Gets the name of the brand.
     *
     * @return string The name of the brand.
     */
    public function getBrandName(): string {
        return $this->brand_name;
    }
}
?>