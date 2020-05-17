<?php include 'view/header.php'; ?>
    <main>
        <h2>Category/Product Search</h2>
        <form action="index.php" method="post">
                <input type="hidden" name="action" value="searchCategories">
                <label for="categoryName">Category Name:</label>
                <input type="text" name="categoryName" id="categoryName">
                <input type="submit" value="Search">
            </form>
            <br/>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="searchProductIds">
                <label for="productId">Product Id:</label>
                <input type="text" name="productId" id="productId">
                <input type="submit" value="Search">
            </form>
            <br/>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="searchProductNames">
                <label for="productName">Product Name:</label>
                <input type="text" name="productName" id="productName">
                <input type="submit" value="Search">
            </form>
        <?php if ($action === 'categories') : ?>
            <?php if (!$categoryList->isEmpty()) : ?>
                <h2>Results</h2>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php for ($index = 0; $index < $categoryList->size(); $index++) :
                        $category = $categoryList->get($index);
                        $categoryId = htmlspecialchars($category->getId());
                        ?>
                        <tr>
                            <td><?php echo $categoryId; ?></td>
                            <td><?php echo htmlspecialchars($category->getName()); ?></td>
                            <td>
                                <form action="index.php" method="post">
                                <input type="hidden" name="action" value="displayCategory" />
                                <input type="hidden" name="categoryId"
                                    value="<?php echo $categoryId; ?>" />
                                <input type="submit" value="Select" />
                                </form>
                            </td>
                            <td>
                                <?php
                                    $url = "index.php?action=deleteCategory&id=$categoryId";
                                ?>
                                <a href="<?php echo $url; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </table>
            <?php else: // This is the accounts array is empty (size === 0) ?>
                <p>No categories found with that name.</p>
            <?php endif; ?>


        <?php elseif ($action === 'id') : ?>
            <?php if ($message==="") : ?>
                <h2>Results</h2>
                <table>
                    <tr>
                        <th>CategoryId</th>
                        <th>ProductId</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php 
                        $category = htmlspecialchars($product->getCategory());
                        $productId = htmlspecialchars($product->getId());
                        $productName = htmlspecialchars($product->getName());
                        $productPrice = htmlspecialchars($product->getPrice());
                    ?>
                    <tr>
                        <td><?php echo $category; ?></td>
                        <td><?php echo $productId; ?></td>
                        <td><?php echo $productName; ?></td>
                        <td><?php echo $productPrice; ?></td>
                        <td>
                            <form action="index.php" method="post">
                            <input type="hidden" name="action" value="displayProduct" />
                            <input type="hidden" name="productId"
                                value="<?php echo $productId; ?>" />
                            <input type="submit" value="Select" />
                            </form>
                        </td>
                        <td>
                            <?php
                                $url = "index.php?action=deleteProduct&id=$productId";
                            ?>
                            <a href="<?php echo $url; ?>">Delete</a>
                        </td>
                    </tr>
                </table>              
            <?php endif; //else, an error occured. ?>

        <?php elseif ($action === 'name') : ?>
            <?php if (!$productList->isEmpty()) : ?>
                <h2>Results</h2>
                <table>
                    <tr>
                        <th>CategoryId</th>
                        <th>ProductId</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php for ($index = 0; $index < $productList->size(); $index++) :
                        $product = $productList->get($index);
                        $category = htmlspecialchars($product->getCategory());
                        $productId = htmlspecialchars($product->getId());
                        $productName = htmlspecialchars($product->getName());
                        $productPrice = htmlspecialchars($product->getPrice());
                        ?>
                        <tr>
                            <td><?php echo $category; ?></td>
                            <td><?php echo $productId; ?></td>
                            <td><?php echo $productName; ?></td>
                            <td><?php echo $productPrice; ?></td>
                            <td>
                                <form action="index.php" method="post">
                                <input type="hidden" name="action" value="displayProduct" />
                                <input type="hidden" name="productId"
                                    value="<?php echo $productId; ?>" />
                                <input type="submit" value="Select" />
                                </form>
                            </td>
                            <td>
                                <?php
                                    $url = "index.php?action=deleteProduct&id=$productId";
                                ?>
                                <a href="<?php echo $url; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </table>
            <?php else: // This is the accounts array is empty (size === 0) ?>
                <p>No products found with that name.</p>
            <?php endif; ?> 
        <?php endif; #else, no action selected.?>

        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php else: $message = "Please search for a category or product" ?>    
        <?php endif; ?>
        
        <h2>Add a new category/product</h2>
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="addProduct">
            <input type="submit" value="Add Product">
        </form>
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="addCategory">
            <input type="submit" value="Add Category">
        </form>
        

    </main>
<?php include 'view/footer.php'; ?>