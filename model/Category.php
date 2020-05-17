<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

/**
 * Class containing all of the information for a single category.
 */
class Category{
    /**
     * @var int $id the unique id for this category. 
     */
    private $id = 0;

    /**
     * @var string $name the name of the category.
     */
    private $name = '';

    /**
     * @var CategoryDAOImpl $dao the dao used for 
     *  accessing information from the database
     */
    private $dao;

    /**
     * Constructs and initializes the class and assigns the information for the Category.
     *
     * @param integer $id the unique id for this category
     * @param string $name the name of the category
     */
    public function __construct(int $id = 0, string $name = ''){
        $this->setId($id);
        $this->setName($name);
        $this->dao = new CategoryDAOImpl();
    }

    /**
     * gets the ID for this Category.
     *
     * @return integer the ID of this Category.
     */
    public function getId(): int {
        return $this->id;
    }
    
    /**
     * sets the ID for this Category
     *
     * @param integer $id the id to be used for this Category.
     * @return void
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * gets the name for this Category.
     *
     * @return string the name of this category.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * sets the name for this category.
     *
     * @param string $name the name for this category.
     * @return void
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * calls the DAO to save this instance of this class
     *
     * @return boolean if this class was properly saved in the DAO.
     */
    public function save(): bool {
        try {
            return $this->dao->addCategory($this);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * calls the DAO to delete this category.
     *
     * @return boolean if the category was deleted.
     */
    public function delete(): bool {
        return $this->dao->deleteCategory($this->getId());
    }

    /**
     * calls the DAO to update this instance of this category.
     *
     * @return boolean if the category was updated.
     */
    public function update(): bool {
        return $this->dao->updateCategory($this);
    }

    /**
     * calls the DAO to retrieve the information for the category with the specified ID.
     *
     * @param integer $id the id to be queried from the DAO.
     * @return Category a new Category instance with the information from the DAO.
     * 
     * Throws RuntimeException if there is an error while accessing the information.
     */
    public static function getById(int $id): Category {
        $dao = new CategoryDAOImpl();
        try {
            return $dao->getCategoryById($id);
        } catch (Exception $e) {
            throw new RuntimeException("Category $id not found");
        }
    }

    /**
     * Converts the information of this class to a string.
     *
     * @return string the string representation of the class information.
     */
    public function __toString() {
        return "$this->id :: $this->name";
    }
}