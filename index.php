<?php
include 'config.php';

// Удаление выбранных товаров
if (isset($_POST['delete_products'])) {
    $selected_products = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];
    foreach ($selected_products as $product_id) {
        $delete_sql = "DELETE FROM products WHERE id = $product_id";
        $conn->query($delete_sql);
    }
}

// Получаем список уникальных категорий из базы данных
$categories_sql = "SELECT DISTINCT category FROM products";
$categories_result = $conn->query($categories_sql);

// Фильтрация по категории
$category_filter = isset($_GET['category']) ? $_GET['category'] : "";

// Сортировка по цене
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : "";
$sort_sql = "";
if ($sort_order == "asc") {
    $sort_sql = "ORDER BY price ASC";
} elseif ($sort_order == "desc") {
    $sort_sql = "ORDER BY price DESC";
}

// Формируем SQL-запрос для получения товаров с учетом фильтрации и сортировки
$sql = "SELECT id, name, description, price, category FROM products";
if ($category_filter) {
    $sql .= " WHERE category = '$category_filter'";
}
$sql .= " " . $sort_sql;

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Список электроники</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete() {
            return confirm("Вы действительно хотите удалить выбранные товары?");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Список электроники</h1>
        <a href="add_product.php" class="btn">Добавить новый продукт</a>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="filters">
            <div>
                <label for="category-filter">Фильтр по категории:</label>
                <select name="category" id="category-filter">
                    <option value="">Все категории</option>
                    <?php while ($category_row = $categories_result->fetch_assoc()) { ?>
                        <option value="<?php echo $category_row['category']; ?>" <?php if ($category_row['category'] == $category_filter) echo "selected"; ?>><?php echo $category_row['category']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="sort-filter">Сортировка по цене:</label>
                <select name="sort" id="sort-filter">
                    <option value="">По умолчанию</option>
                    <option value="asc" <?php if ($sort_order == "asc") echo "selected"; ?>>Сначала недорогие</option>
                    <option value="desc" <?php if ($sort_order == "desc") echo "selected"; ?>>Сначала дорогие</option>
                </select>
            </div>
            <input type="submit" value="Применить" class="btn">
        </form>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return confirmDelete()" class="product-list">
            <table>
                <tr>
                    <th></th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Категория</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox" name="selected_products[]" value="<?php echo $row['id']; ?>"></td>      
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                    </tr>
                <?php } ?>
            </table>
            <input type="submit" name="delete_products" value="Удалить выбранные" class="btn">
        </form>
    </div>
</body>
</html>
