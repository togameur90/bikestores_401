<?php
// src/Entity/Stock.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents the stock inventory of a product in a specific store.
 *
 * @ORM\Entity
 * @ORM\Table(name="stocks")
 */
class Stock {
    
    /**
     * The unique identifier for the stock record.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $stock_id;

    /**
     * The quantity of the product available in stock.
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private ?int $quantity = null;

    /**
     * The store where the stock is located.
     *
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id", nullable=true)
     * @var Store|null
     */
    private ?Store $store = null;

    /**
     * The specific product associated with this stock record.
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id", nullable=true)
     * @var Product|null
     */
    private ?Product $product = null;

    /**
     * Returns a string representation of the stock.
     *
     * @return string A string containing the stock ID and quantity.
     */
    public function __toString(): string {
        return "Stock {$this->stock_id} : Quantité {$this->quantity}\n";
    }

    // --- GETTERS & SETTERS ---

    /**
     * Gets the unique identifier of the stock record.
     *
     * @return int The stock ID.
     */
    public function getStockId(): int { return $this->stock_id; }

    /**
     * Sets the quantity available in stock.
     *
     * @param int|null $quantity The stock quantity.
     * @return self Returns the current instance for method chaining.
     */
    public function setQuantity(?int $quantity): self { $this->quantity = $quantity; return $this; }
    
    /**
     * Gets the quantity available in stock.
     *
     * @return int|null The stock quantity.
     */
    public function getQuantity(): ?int { return $this->quantity; }

    /**
     * Sets the associated store for this stock.
     *
     * @param Store|null $store The store entity.
     * @return self Returns the current instance for method chaining.
     */
    public function setStore(?Store $store): self { $this->store = $store; return $this; }
    
    /**
     * Gets the associated store for this stock.
     *
     * @return Store|null The associated store entity.
     */
    public function getStore(): ?Store { return $this->store; }

    /**
     * Sets the associated product for this stock.
     *
     * @param Product|null $product The product entity.
     * @return self Returns the current instance for method chaining.
     */
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }
    
    /**
     * Gets the associated product for this stock.
     *
     * @return Product|null The associated product entity.
     */
    public function getProduct(): ?Product { return $this->product; }
}
?>