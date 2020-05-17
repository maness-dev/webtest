<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

class ProductDAOImpl implements ProductDAO
{
    /**
     * @var PDO $connection the connection object for accessing the DB.
     */
    private $connection;

    /**
     * @var string $DSN the vendor specific connection string to the DB.
     */
    private const DSN = 'mysql:host=localhost;dbname=store';

    /**
     * @var string $USER_NAME the username for logging into the DB
     */
    private const USER_NAME = 'root';

    /**
     * @var string $PASSWORD the password for logging into the DB.
     */
    private const PASSWORD = 'St10057192';

    /**
     * @var string $GET_PRODUCTS_BY_NAME_QUERY Query string for getting all items 
     *  in the table that contains the specified name.
     */
    private const GET_PRODUCTS_BY_NAME_QUERY =
        'SELECT category_id, product_id, product_name, product_price FROM products WHERE product_name LIKE :name';

    /**
     * @var string $GET_PRODUCT_BY_ID Query string for getting the item
     *  in the table that contains the specified id.
     */
    private const GET_PRODUCT_BY_ID =
       'SELECT category_id, product_id, product_name, product_price FROM products WHERE product_id = :id';

    /**
     * @var string $INSERT_PRODUCT_QUERY Query string for adding the item to the DB.
     */
    private const INSERT_PRODUCT_QUERY =
        'INSERT INTO products (category_id, product_id, product_name, product_price) VALUES (:cid, :id, :name, :price)';

    /**
     * @var string $UPDATE_PRODUCT_QUERY Query string for updating the item in the DB.
     */
    private const UPDATE_PRODUCT_QUERY =
        'UPDATE products SET category_id = :cid, product_name = :name, product_price = :price WHERE product_id = :id';

    /**
     * @var string $DELETE_PRODUCT_QUERY Query string for deleting the item from the DB.
     */
    private const DELETE_PRODUCT_QUERY =
        'DELETE FROM products WHERE product_id = :id';

    /**
     * @var string $DELETE_ALL_IN_CATEGORY Query string for deleting the items from the DB with the specified category id.
     */
    private const DELETE_ALL_IN_CATEGORY =
        'DELETE FROM products WHERE category_id = :cid';

    /**
     * takes individual results and converts it to a product object.
     *
     * @param array $resultSet the result to be parsed.
     * @return Product the product object made form the result data.
     */
    private function mapProduct(array $resultSet): Product {
        $cid = intval($resultSet['category_id']);
        $id = intval($resultSet['product_id']);
        $name = $resultSet['product_name'];
        $price = floatval($resultSet['product_price']);
        return new Product($id, $name, $cid, $price);
    }

    /**
     * takes results from a query and forms a list of product objects.
     *
     * @param array $resultSet the result form the query to be parsed
     * @return ProductList the list of product objects.
     */
    private function mapProducts(array $resultSet): ProductList {
        $products = new ProductList();
        foreach ($resultSet as $result) {
            $products->addProduct($this->mapProduct($result));
        }
        return $products;
    }

    /**
     * constructs this DB and initiallizes the PDO object
     *
     * @param PDO $connection optional connection string.
     */
    public function __construct(PDO $connection = null) {
        $this->connection = $connection;
        if ($this->connection === null) {
            $this->connection = new PDO(self::DSN, self::USER_NAME, self::PASSWORD);
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } // else, PDO is set, doNothing();
    }

    /**
     * calls the DB to get a list of products that contain the specified name.
     * @param string $name the name to search for in the DB.
     * @return ProductList a list of products.
     */
    public function getProductsByName(string $name): ProductList {
        $statement = $this->connection->prepare(self::GET_PRODUCTS_BY_NAME_QUERY);
        $statement->bindValue(':name', "%$name%");
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $this->mapProducts($result);
    }

    /**
     * calls the DB to get a product with the specific id.
     * @param integer $id the id to be searched in the DB.
     * @return Product a new product with the information returned from the DB.
     */
    public function getProductById(int $id): Product {
        $statement = $this->connection->prepare(self::GET_PRODUCT_BY_ID);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $result = $statement->fetch();
        $rowCount = $statement->rowCount();
        $statement->closeCursor();
        if($rowCount > 0){
            try{
                return $this->mapProduct($result);
            } catch (Exception $e){
                throw new RuntimeException("Product $id not found");
            }
        } else {
            throw new RuntimeException("Product $id not found");
        }
    }

    /**
     * calls the DB to add an instance of the product class.
     *
     * @param Product $product the product to be saved.
     * @return boolean if the product was saved.
     */
    public function addProduct(Product $product): bool {
        #'(:cid, :id, :name, :price)';
        $statement = $this->connection->prepare(self::INSERT_PRODUCT_QUERY);
        $statement->bindValue(':id', $product->getId());
        $statement->bindValue(':name', $product->getName());
        $statement->bindValue(':cid', $product->getCategory());
        $statement->bindValue(':price', $product->getPrice());
        $rowCount = $statement->execute();
        $statement->closeCursor();
        return $rowCount === 1;
    }

    /**
     * calls the DB to update an instance of the product class.
     *
     * @param Product $product the product to be updated.
     * @return boolean if the product was updated.
     */
    public function updateProduct(Product $product): bool {
        $statement = $this->connection->prepare(self::UPDATE_PRODUCT_QUERY);
        $statement->bindValue(':id', $product->getId());
        $statement->bindValue(':name', $product->getName());
        $statement->bindValue(':cid', $product->getCategory());
        $statement->bindValue(':price', $product->getPrice());
        $statement->execute();
        $rowCount = $statement->rowCount();
        $statement->closeCursor();
        return $rowCount === 1;
    }

    /**
     * calls the DB to delete an instance of the product class.
     *
     * @param Product $product the product to be deleted.
     * @return boolean if the product was deleted.
     */
    public function deleteProduct(int $id): bool {
        $statement = $this->connection->prepare(self::DELETE_PRODUCT_QUERY);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $rowCount = $statement->rowCount();
        $statement->closeCursor();
        return $rowCount === 1;
    }

    /**
     * calls the DB to delete all products that had the specified category id.
     *
     * @param int $cid the cid of the products to be deleted.
     * @return boolean if the products were deleted.
     */
    function deleteAllProductsInCategory(int $cid): bool{
        $statement = $this->connection->prepare(self::DELETE_ALL_IN_CATEGORY);
        $statement->bindValue(':cid', $cid);
        $statement->execute();
        $rowCount = $statement->rowCount();
        $statement->closeCursor();
        return $rowCount === 1;
    }
}