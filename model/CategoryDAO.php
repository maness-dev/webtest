<?php
declare(strict_types = 1);

spl_autoload_register(function ($class){
    include "$class.php";
});

/**
 * Interface for DAO methods needed for communicating with the DAO.
 */
interface CategoryDAO
{
    /**
     * calls the DAO to get all category information.
     *
     * @return CategoryList a list of categories.
     */
    public function getAllCategories(): CategoryList;

    /**
     * calls the DAO to get a list of categories that contain the specified name.
     * @param string $name the name to search for in the DAO.
     * @return CategoryList a list of categories.
     */
    public function getCategoriesByName(string $name): CategoryList;

    /**
     * calls the DAO to get a category with the specific id.
     * @param integer $id the id to be searched in the DAO.
     * @return Category a new category with the information returned from the DAO.
     */
    public function getCategoryById(int $id): Category;

    /**
     * calls the DAO to add an instance of the category class.
     *
     * @param Category $category the category to be saved.
     * @return boolean if the category was saved.
     */
    public function addCategory(Category $category): bool;

    /**
     * calls the DAO to update an instance of the category class.
     *
     * @param Category $category the category to be updated.
     * @return boolean if the category was updated.
     */
    public function updateCategory(Category $category): bool;

    /**
     * calls the DAO to delete an instance of the category class.
     *
     * @param Category $category the category to be deleted.
     * @return boolean if the category was deleted.
     */
    public function deleteCategory(int $id): bool;
}