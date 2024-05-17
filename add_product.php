<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $sql = "INSERT INTO products (name, description, price, category) VALUES ('$name', '$description', '$price', '$category')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Товар успешно добавлен!";
    } else {
        $error_message = "Ошибка при добавлении товара: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Добавить товар</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Добавить новый товар</h1>
        <?php if (isset($success_message)) { ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="add-product-form">
            <div>
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="description">Описание:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div>
                <label for="price">Цена:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div>
                <label for="category">Категория:</label>
                <input type="text" id="category" name="category" required>
            </div>
            <input type="submit" value="Добавить продукт" class="btn">
            <a href="index.php" class="btn">Вернуться к списку</a>
        </form>
    </div>
</body>
</html>
