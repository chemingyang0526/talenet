<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity
* @ORM\Table(name="products")
*/
class Product
{
	/**
	  @ORM\Column(type="integer")
	  @ORM\Id
	  @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;
	/**
	* @ORM\Column(type="string", length=100)
    * @Assert\NotBlank(message="Please enter a product name")
	*/
	private $name;
	/**
	* @ORM\ManyToOne(targetEntity="Category")
    * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
    * @Assert\NotBlank(message="Please choose a category")
    */
	private $category;
	/**
	* @ORM\Column(type="string", length=25)
    @Assert\NotBlank(message="Please enter a sku")
	*/
	private $sku;
	/**
    * @ORM\Column(type="float")
    @Assert\NotBlank(message="Please enter a price")
	*/
	private $price;
	/**
	* @ORM\Column(type="integer")
    @Assert\NotBlank(message="Please enter a quantity")
	*/
	private $quantity;
    /**
    * @ORM\Column(type="integer")
    */
    private $category_id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sku.
     *
     * @param string $sku
     *
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku.
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set price.
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set category.
     *
     * @param \AppBundle\Entity\Category|null $category
     *
     * @return Product
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;
        if (!empty($this->category)) {
            $this->category_id = $this->category->getId();
        } 
        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategoryId($cat_id) {
        $this->category_id = $cat_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }
}
