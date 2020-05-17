<?php include 'view/header.php'; ?>
    <main>
        <?php if($action === 'updateCategory' || $action === 'addCategoryInfo') : ?>
            <h2>Update/Add Category</h2>
            <form action="index.php" method="post" id="aligned">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'addCategoryInfo'): ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id"
                            value="<?php echo htmlspecialchars($category->getId()); ?>">
                    <br />
                <?php else : ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id"
                            value="<?php echo htmlspecialchars($category->getId()); ?>" readonly>
                    <br />
                <?php endif; ?>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name"
                        value="<?php echo htmlspecialchars($category->getName()); ?>">
                <br />
                <label>&nbsp;</label>
                <input type="submit" value="<?php echo $buttonText; ?>">
                <br />
            </form>
        <?php elseif ($action === 'updateProduct' || $action === 'addProductInfo'): ?>
            <h2>Update/Add Prodcut</h2>
            <form action="index.php" method="post" id="aligned">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <label for="categories">Select a Category: </label>
                <select id="categories" name="categories">
                    <?php for ($index = 0; $index < $categoryList->size(); $index++) :
                            $category = $categoryList->get($index);
                            $categoryName = htmlspecialchars($category->getName());
                    ?>
                    <option value="<?php echo $category->getId(); ?>"><?php echo $categoryName; ?></option>
                    <?php endfor; ?>
                </select>
                <br />
                <?php if ($action === 'addProductInfo') : ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id"
                            value="<?php echo htmlspecialchars($product->getId()); ?>">
                    <br />
                <?php else : ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id"
                            value="<?php echo htmlspecialchars($product->getId()); ?>" readonly>
                    <br />
                <?php endif; ?>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name"
                        value="<?php echo htmlspecialchars($product->getName()); ?>">
                <br />
                <label for="price">Price:</label>
                <input type="text" name="price" id="price"
                        value="<?php echo htmlspecialchars($product->getPrice()); ?>">
                <br />
                <label>&nbsp;</label>
                <input type="submit" value="<?php echo $buttonText; ?>">
                <br />
            </form>
            <br/>
        <?php endif; #else, nothing to display. ?>
        <p><a href="index.php">Search</a></p>
    </main>
<?php include 'view/footer.php'; ?>