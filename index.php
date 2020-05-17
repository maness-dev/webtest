<?php 
// spl_autoload_register(function ($class) {
//     include "model/$class.php";
// });

include 'model/CategoryList.php';
include 'model/ProductList.php';



$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'search';
    } // else, we have an action, do_nothing();
} // else, we have an action, do_nothing();

$categoryList = new CategoryList();
$productList = new ProductList();


switch ($action) {
    case 'search':
        include 'search.php';
        break;
        
    case 'searchCategories':
        $key = filter_input(INPUT_POST, 'categoryName');
        $categoryList = CategoryList::getCategoryByName($key);
        $action = 'categories';
        include 'search.php';
        break;

    case 'searchProductIds':
        $key = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);
        if ($key === false || $key === null){
            $message = "Enter a valid product number.";
            include 'search.php';
        } else {
            $product = "";
            $message = "";
            try {
                $product = Product::getById($key);
            } catch (RuntimeException $e) {
                $message = $e->getMessage();
            }
            $action = 'id';
            include 'search.php';
        }
        break;

    case 'searchProductNames':
        $key = filter_input(INPUT_POST, 'productName');
        $productList = ProductList::getProductsByName($key);
        $action = 'name';
        include 'search.php';
        break;     

    case 'displayCategory':
        $id = filter_input(INPUT_POST, 'categoryId', FILTER_VALIDATE_INT);
        $category = Category::getById($id);
        $buttonText = 'Update';
        $action = 'updateCategory';
        include 'display.php';
        break;

    case 'displayProduct':
        $categoryList = CategoryList::getAllCategories();
        $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);
        $product = Product::getById($id);
        $buttonText = 'Update';
        $action = 'updateProduct';
        include 'display.php';
        break;

    case 'addCategory':
        $action = 'addCategoryInfo';
        $buttonText = 'Save';
        $category = new Category();
        include 'display.php';
        break;

    case 'addProduct':
        $categoryList = CategoryList::getAllCategories();
        $action = 'addProductInfo';
        $buttonText = 'Save';
        $product = new Product();
        include 'display.php';
        break;

    case 'addCategoryInfo':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $category = new Category($id, $name);
        $category->save();
        $message = "Category saved $category";
        include 'search.php';
        break;
    case 'addProductInfo':
        $cid = filter_input(INPUT_POST, 'categories', FILTER_VALIDATE_INT);
        $message = "";
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null){
            $message = "Enter a valid product number.";
            include 'search.php';
        }
        $name = filter_input(INPUT_POST, 'name');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        if ($price === false || $price === null){
            $message = "Enter a valid product price.";
            include 'search.php';
        }
        $product = new Product($id, $name, $cid, $price);
        $product->save();
        $message = "Product saved -> $product";
        include 'search.php';
        break;

    case 'updateCategory':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $category = new Category($id, $name);
        $category->update();
        $message = "Category updated -> $category";
        include 'search.php';
        break;

    case 'updateProduct':
        $cid = filter_input(INPUT_POST, 'categories', FILTER_VALIDATE_INT);
        $message = "";
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null){
            $message = "Enter a valid product number.";
            include 'search.php';
        }
        $name = filter_input(INPUT_POST, 'name');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        if ($price === false || $price === null){
            $message = "Enter a valid product price.";
            include 'search.php';
        }
        $product = new Product($id, $name, $cid, $price);
        $product->update();
        $message = "Product updated -> $product";
        include 'search.php';
        break;
    case 'deleteCategory':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        ProductList::deleteAllProductsInCategory($id);
        $category = new Category($id);
        $category->delete();
        $message = "Category ($id) deleted";
        include 'search.php';
        break;
    case 'deleteProduct':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $product = new Product($id);
        $product->delete();
        $message = "Product ($id) deleted";
        include 'search.php';
        break;
}