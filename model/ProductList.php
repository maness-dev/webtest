<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

class ProductList
{
    /**
     * @var array List of product objects
     */
    private $products;

    /**
     * constructs and initializes an instance of this class then sets an empty array.
     */
    public function __construct() {
        $this->products = Array();
    }

    /**
     * calls the DAO to add an instance of the product class.
     *
     * @param Product $product the product to be saved.
     * @return void
     */
    public function addProduct(Product $product) {
        $this->products[] = $product;
    }

    /**
     * gets the size of the array
     *
     * @return integer the size of the array
     */
    public function size(): int {
        return count($this->products);
    }

    /**
     * gets the item in the array at the specified index
     *
     * @param integer $index the index being queried in the array.
     * @return Product the object at the specified index.
     */
    public function get(int $index): Product {
        return $this->products[$index];
    }

    /**
     * checks if the array's size is greater than zero or empty.
     *
     * @return boolean if the array is empty.
     */
    public function isEmpty(): bool {
        return $this->size() === 0;
    }

    /**
     * calls the DAO to get a list of products that contain the specified name.
     * @param string $name the name to search for in the DAO.
     * @return ProductList a list of products.
     */
    public static function getProductsByName(string $name): ProductList {
        $dao = new ProductDAOImpl();
        return $dao->getProductsByName($name);
    }

    /**
     * calls the DAO to delete products with a specified category ID.
     * @param integer $cid the category id used to delete products with.
     * @return boolean if the products have been deleted.
     */
    public static function deleteAllProductsInCategory(int $cid): bool {
        $dao = new ProductDAOImpl();
        return $dao->deleteAllProductsInCategory($cid);
    }

    /**
     * Converts the information of this class to a string.
     *
     * @return string the string representation of the class information.
     */
    public function __toString() {
        $builder = '';
        foreach ($this->products as $product) {
            $builder .= "$product <br />";
        }
        return $builder;
    }
}