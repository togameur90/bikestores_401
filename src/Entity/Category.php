<?php
// src/Entity/Category.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a product category in the Bike Stores application.
 *
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category {
    
    /**
     * The unique identifier for the category.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $category_id;

    /**
     * The name of the category.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $category_name;

    /**
     * Returns a string representation of the category.
     *
     * @return string A string containing the category ID and name.
     */
    public function __toString(): string {
        return "Catégorie {$this->category_id} : {$this->category_name}\n";
    }

    /**
     * Gets the unique identifier of the category.
     *
     * @return int The category ID.
     */
    public function getCategoryId(): int {
        return $this->category_id;
    }

    /**
     * Sets the name of the category.
     *
     * @param string $category_name The name of the category.
     * @return self Returns the current instance for method chaining.
     */
    public function setCategoryName(string $category_name): self {
        $this->category_name = $category_name;
        return $this;
    }

    /**
     * Gets the name of the category.
     *
     * @return string The name of the category.
     */
    public function getCategoryName(): string {
        return $this->category_name;
    }
}
?>