<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

/**
 * Interface for DAO methods needed for communicating with the DAO.
 */
interface ProductDAO
{
    /**
     * calls the DAO to get a list of products that contain the specified name.
     * @param string $name the name to search for in the DAO.
     * @return ProductList a list of products.
     */
    public function getProductsByName(string $name): ProductList;

    /**
     * calls the DAO to get a product with the specific id.
     * @param integer $id the id to be searched in the DAO.
     * @return Product a new product with the information returned from the DAO.
     */
    public function getProductById(int $id): Product;

    /**
     * calls the DAO to add an instance of the product class.
     *
     * @param Product $product the product to be saved.
     * @return boolean if the product was saved.
     */
    public function addProduct(Product $product): bool;

    /**
     * calls the DAO to update an instance of the product class.
     *
     * @param Product $product the product to be updated.
     * @return boolean if the product was updated.
     */
    public function updateProduct(Product $product): bool;

    /**
     * calls the DAO to delete an instance of the product class.
     *
     * @param Product $product the product to be deleted.
     * @return boolean if the product was deleted.
     */
    public function deleteProduct(int $id): bool;

    /**
     * calls the DAO to delete all products that had the specified category id.
     *
     * @param int $cid the cid of the products to be deleted.
     * @return boolean if the products were deleted.
     */
    public function deleteAllProductsInCategory(int $cid): bool;
}