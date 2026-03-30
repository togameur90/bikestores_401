<?php
// src/Entity/Product.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a product (bike) in the Bike Stores application.
 *
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product {
    
    /**
     * The unique identifier for the product.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $product_id;

    /**
     * The name of the product.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    private string $product_name;

    /**
     * The model year of the product.
     *
     * @ORM\Column(type="smallint")
     * @var int
     */
    private int $model_year;

    /**
     * The original listing price of the product.
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private float $list_price;

    /**
     * The brand that manufactures the product.
     *
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id", nullable=false)
     * @var Brand|null
     */
    private ?Brand $brand = null;

    /**
     * The category this product belongs to.
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id", nullable=false)
     * @var Category|null
     */
    private ?Category $category = null;

    /**
     * Returns a string representation of the product.
     *
     * @return string A detailed string about the product.
     */
    public function __toString(): string {
        $nomMarque = $this->brand !== null ? $this->brand->getBrandName() : 'Inconnue';
        return "Produit {$this->product_id} : {$this->product_name} ({$this->model_year}) - Marque: {$nomMarque}\n";
    }

    // --- GETTERS & SETTERS ---

    /**
     * Gets the unique identifier of the product.
     *
     * @return int The product ID.
     */
    public function getProductId(): int { return $this->product_id; }

    /**
     * Sets the name of the product.
     *
     * @param string $product_name The product name.
     * @return self Returns the current instance for method chaining.
     */
    public function setProductName(string $product_name): self { $this->product_name = $product_name; return $this; }
    
    /**
     * Gets the name of the product.
     *
     * @return string The product name.
     */
    public function getProductName(): string { return $this->product_name; }

    /**
     * Sets the model year of the product.
     *
     * @param int $model_year The model year.
     * @return self Returns the current instance for method chaining.
     */
    public function setModelYear(int $model_year): self { $this->model_year = $model_year; return $this; }
    
    /**
     * Gets the model year of the product.
     *
     * @return int The model year.
     */
    public function getModelYear(): int { return $this->model_year; }

    /**
     * Sets the list price of the product.
     *
     * @param float $list_price The list price.
     * @return self Returns the current instance for method chaining.
     */
    public function setListPrice(float $list_price): self { $this->list_price = $list_price; return $this; }
    
    /**
     * Gets the list price of the product.
     *
     * @return float The list price.
     */
    public function getListPrice(): float { return $this->list_price; }

    /**
     * Sets the brand of the product.
     *
     * @param Brand|null $brand The associated brand entity.
     * @return self Returns the current instance for method chaining.
     */
    public function setBrand(?Brand $brand): self { $this->brand = $brand; return $this; }
    
    /**
     * Gets the brand of the product.
     *
     * @return Brand|null The associated brand entity.
     */
    public function getBrand(): ?Brand { return $this->brand; }

    /**
     * Sets the category of the product.
     *
     * @param Category|null $category The associated category entity.
     * @return self Returns the current instance for method chaining.
     */
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
    
    /**
     * Gets the category of the product.
     *
     * @return Category|null The associated category entity.
     */
    public function getCategory(): ?Category { return $this->category; }
}
?>