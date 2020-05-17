<?php
declare(strict_types = 1);

spl_autoload_register(function ($class) {
    include "$class.php";
});

class CategoryList
{
    /**
     * @var array List of Category objects
     */
    private $categories;

    /**
     * constructs and initializes an instance of this class then sets an empty array.
     */
    public function __construct() {
        $this->categories = Array();
    }

    /**
     * calls the DAO to add an instance of the category class.
     *
     * @param Category $category the category to be saved.
     * @return void
     */
    public function addCategory(Category $category) {
        $this->categories[] = $category;
    }

    /**
     * gets the size of the array
     *
     * @return integer the size of the array
     */
    public function size(): int{
        return count($this->categories);
    }

    /**
     * gets the item in the array at the specified index
     *
     * @param integer $index the index being queried in the array.
     * @return Category the object at the specified index.
     */
    public function get(int $index): Category{
        return $this->categories[$index];
    }

    /**
     * checks if the array's size is greater than zero or empty.
     *
     * @return boolean if the array is empty.
     */
    public function isEmpty(): bool{
        return $this->size() === 0;
    }

    /**
     * calls the DAO to get a list of categories that contain the specified name.
     * @param string $name the name to search for in the DAO.
     * @return CategoryList a list of categories.
     */
    public static function getCategoryByName(string $name): CategoryList {
        $dao = new CategoryDAOImpl();
        return $dao->getCategoriesByName($name);
    }

    /**
     * calls the DAO to get all category information.
     *
     * @return CategoryList a list of categories.
     */
    public static function getAllCategories(): CategoryList {
        $dao = new CategoryDAOImpl();
        return $dao->getAllCategories();
    }

    /**
     * Converts the information of this class to a string.
     *
     * @return string the string representation of the class information.
     */
    public function __toString() {
        $builder = '';
        foreach ($this->categories as $category) {
            $builder .= "$category <br />";
        }
        return $builder;
    }
}