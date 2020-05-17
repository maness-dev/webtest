<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

class CategoryDAOImpl implements CategoryDAO
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
     * @var string $GET_ALL_CATEGORIES_QUERY Query string for getting all items in the table.
     */
    private const GET_ALL_CATEGORIES_QUERY =
        'SELECT category_id, category_name FROM categories';

    /**
     * @var string $GET_CATEGORIES_BY_NAME_QUERY Query string for getting all items 
     *  in the table that contains the specified name.
     */
    private const GET_CATEGORIES_BY_NAME_QUERY = 
        'SELECT category_id, category_name FROM categories WHERE category_name LIKE :name';

    /**
     * @var string $GET_CATEGORY_BY_ID Query string for getting the item
     *  in the table that contains the specified id.
     */
    private const GET_CATEGORY_BY_ID =
        'SELECT category_id, category_name FROM categories WHERE category_id = :id';

    /**
     * @var string $INSERT_CATEGORY_QUERY Query string for adding the item to the DB.
     */
    private const INSERT_CATEGORY_QUERY =
        'INSERT INTO categories (category_id, category_name) VALUES (:id, :name)';

    /**
     * @var string $UPDATE_CATEGORY_QUERY Query string for updating the item in the DB.
     */
    private const UPDATE_CATEGORY_QUERY =
        'UPDATE categories SET category_name = :name WHERE category_id = :id';
    
    /**
     * @var string $DELETE_CATEGORY_QUERY Query string for deleting the item from the DB.
     */
    private const DELETE_CATEGORY_QUERY =
        'DELETE FROM categories WHERE category_id = :id';
 
    /**
     * takes individual results and converts it to a category object.
     *
     * @param array $resultSet the result to be parsed.
     * @return Category the category object made form the result data.
     */
    private function mapCategory(array $resultSet): Category {
        $id = intval($resultSet['category_id']);
        $name = $resultSet['category_name'];
        return new Category($id, $name);
    }
 
    /**
     * takes results from a query and forms a list of category objects.
     *
     * @param array $resultSet the result form the query to be parsed
     * @return CategoryList the list of category objects.
     */
    private function mapCategories(array $resultSet): CategoryList {
        $categories = new CategoryList();
        foreach ($resultSet as $result) {
            $categories->addCategory($this->mapCategory($result));
        }
        return $categories;
    }

    /**
     * constructs this DAO and initiallizes the PDO object
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
     * calls the DB to get all category information.
     *
     * @return CategoryList a list of categories.
     */
    public function getAllCategories(): CategoryList{
        $statement = $this->connection->prepare(self::GET_ALL_CATEGORIES_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $this->mapCategories($result);
    }

    /**
     * calls the DB to get a list of categories that contain the specified name.
     * @param string $name the name to search for in the DAO.
     * @return CategoryList a list of categories.
     */
    public function getCategoriesByName(string $name): CategoryList {
        $statement = $this->connection->prepare(self::GET_CATEGORIES_BY_NAME_QUERY);
        $statement->bindValue(':name', "%$name%");
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $this->mapCategories($result);
    }

    /**
     * calls the DB to get a category with the specific id.
     * @param integer $id the id to be searched in the DAO.
     * @return Category a new category with the information returned from the DAO.
     */
    public function getCategoryById(int $id): Category {
        $statement = $this->connection->prepare(self::GET_CATEGORY_BY_ID);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        if (count($result) > 0) {
            return $this->mapCategory($result);
        } else {
            throw new RuntimeException("Category $id not found");
        }
    }

    /**
     * calls the DB to add an instance of the category class.
     *
     * @param Category $category the category to be saved.
     * @return boolean if the category was saved.
     */
    public function addCategory(Category $category): bool {
        $statement = $this->connection->prepare(self::INSERT_CATEGORY_QUERY);
        $statement->bindValue(':id', $category->getId());
        $statement->bindValue(':name', $category->getName());
        $rowCount = $statement->execute();
        $statement->closeCursor();
        return $rowCount === 1;
    }

    /**
     * calls the DB to update an instance of the category class.
     *
     * @param Category $category the category to be updated.
     * @return boolean if the category was updated.
     */
    public function updateCategory(Category $category): bool {
        $statement = $this->connection->prepare(self::UPDATE_CATEGORY_QUERY);
        $statement->bindValue(':name', $category->getName());
        $statement->bindValue(':id', $category->getId());
        $statement->execute();
        $rowCount = $statement->rowCount();
        $statement->closeCursor();
        return $rowCount === 1;
    }

    /**
     * calls the DB to delete an instance of the category class.
     *
     * @param Category $category the category to be deleted.
     * @return boolean if the category was deleted.
     */
    public function deleteCategory(int $id): bool {
        $statement = $this->connection->prepare(self::DELETE_CATEGORY_QUERY);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $rowCount = $statement->rowCount();
        $statement->closeCursor();
        return $rowCount === 1;
    }
}