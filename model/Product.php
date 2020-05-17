<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

/**
 * Class containing all of the information for a single product.
 */
class Product{
    /**
     * @var int $id the unique id for this product. 
     */
    private $id = 0;

    /**
     * @var string $name the name of the product.
     */
    private $name = '';

    /**
     * @var int $category the category id for this product. 
     */
    private $category = 0;

    /**
     * @var float $price the price for this product. 
     */
    private $price = 0.0;

    /**
     * @var ProductDAOImpl $dao the dao used for 
     *  accessing information from the database
     */
    private $dao;

    /**
     * Constructs and initializes the class and assigns the information for the product.
     *
     * @param integer $id the unique id for this product. 
     * @param string $name the name of the product.
     * @param integer $category the category id for this product. 
     * @param float $price the price for this product.
     */
    public function __construct(int $id = 0, string $name = '', int $category = 0, float $price = 0.0) {
        $this->setId($id);
        $this->setName($name);
        $this->setCategory($category);
        $this->setPrice($price);
        $this->dao = new ProductDAOImpl();
    }

    /**
     * gets the ID for this product.
     *
     * @return integer the ID of this product.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * sets the ID for this product
     *
     * @param integer $id the id to be used for this product.
     * @return void
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * gets the name for this product.
     *
     * @return string the name of this product.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * sets the name for this product.
     *
     * @param string $name the name for this product.
     * @return void
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * gets the category id for this product.
     *
     * @return int the category id of this product.
     */
    public function getCategory(): int {
        return $this->category;
    }

    /**
     * sets the category id for this product.
     *
     * @param integer $category the category id for this product.
     * @return void
     */
    public function setCategory(int $category) {
        $this->category = $category;
    }

    /**
     * gets the price for this product.
     *
     * @return float the price for this product.
     */
    public function getPrice(): float {
        return $this->price;
    }

    /**
     * sets the price for this product.
     *
     * @param float $price the price for this product.
     * @return void
     */
    public function setPrice(float $price) {
        $this->price = $price;
    }

    /**
     * calls the DAO to save this instance of this class
     *
     * @return boolean if this class was properly saved in the DAO.
     */
    public function save(): bool {
        try {
            return $this->dao->addProduct($this);
        } catch (RuntimeException $e) {
            return false;
        }
    }

    /**
     * calls the DAO to delete this product.
     *
     * @return boolean if the product was deleted.
     */
    public function delete(): bool {
        return $this->dao->deleteProduct($this->getId());
    }

    /**
     * calls the DAO to update this instance of this product.
     *
     * @return boolean if the product was updated.
     */
    public function update(): bool {
        return $this->dao->updateProduct($this);
    }

    /**
     * calls the DAO to retrieve the information for the product with the specified ID.
     *
     * @param integer $id the id to be queried from the DAO.
     * @return Product a new product instance with the information from the DAO.
     */
    public static function getById(int $id): Product {
        $dao = new ProductDAOImpl();
        return $dao->getProductById($id);
    }

    /**
     * calls the DAO to retrieve the information for the product with the specified name.
     *
     * @param string $name the name to be queried from the DAO.
     * @return Product a new product instance with the information from the DAO.
     */
    public static function getByName(string $name): Product {
        $dao = new ProductDAOImpl();
        return $dao->getProductByName($name);
    }

    /**
     * Converts the information of this class to a string.
     *
     * @return string the string representation of the class information.
     */
    public function __toString() {
        return "$this->category :: $this->id :: $this->name :: $this->price";
    }
}