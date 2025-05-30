<?php
session_start();
include '../includes/db-connect.php';
include '../includes/functions.php';


if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}


if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = (int)$_GET['id'];
$error_message = '';
$success_message = '';


$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) === 0) {
    header('Location: products.php');
    exit;
}

$product = mysqli_fetch_assoc($result);


$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    $image = trim($_POST['image']);
    $image_2 = trim($_POST['image_2']);
    $image_3 = trim($_POST['image_3']);
    
    if(empty($name) || empty($description) || empty($price) || empty($category_id) || empty($image)) {
        $error_message = 'Por favor, preencha todos os campos obrigatórios.';
    } else {
        $name = mysqli_real_escape_string($conn, $name);
        $description = mysqli_real_escape_string($conn, $description);
        $image = mysqli_real_escape_string($conn, $image);
        $image_2 = mysqli_real_escape_string($conn, $image_2);
        $image_3 = mysqli_real_escape_string($conn, $image_3);
        
        $update_query = "UPDATE products SET 
                        name = '$name',
                        description = '$description',
                        price = $price,
                        stock = $stock,
                        category_id = $category_id,
                        featured = $featured,
                        image = '$image',
                        image_2 = '$image_2',
                        image_3 = '$image_3'
                        WHERE id = $product_id";
        
        if(mysqli_query($conn, $update_query)) {
            $success_message = 'Produto atualizado com sucesso!';
            
            $result = mysqli_query($conn, $query);
            $product = mysqli_fetch_assoc($result);
        } else {
            $error_message = 'Erro ao atualizar produto: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - ProAim Gear Admin</title>
    <link rel="stylesheet" href="/assets/css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'includes/admin-header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin-sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Editar Produto</h1>
                <a href="products.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar para Produtos
                </a>
            </div>
            
            <?php if(!empty($success_message)): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($error_message)): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form action="" method="post" class="admin-form" id="productForm">
                    <div class="form-grid">
                        <div class="search-box">
                            <label for="name">Nome do Produto *</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="filter-box">
                            <label for="category_id">Categoria *</label>
                            <select class="select-category" id="category_id" name="category_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php 
                                mysqli_data_seek($categories_result, 0);
                                while($category = mysqli_fetch_assoc($categories_result)) {
                                    $selected = $product['category_id'] == $category['id'] ? 'selected' : '';
                                    echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="search-box">
                            <label for="price">Preço (R$) *</label>
                            <input type="number" id="price" name="price" min="0.01" step="0.01" value="<?php echo $product['price']; ?>" required>
                        </div>
                        
                        <div class="search-box">
                            <label for="stock">Estoque *</label>
                            <input type="number" id="stock" name="stock" min="0" value="<?php echo $product['stock']; ?>" required>
                        </div>
                        
                        <div class="search-box">
                            <label for="image">URL da Imagem Principal *</label>
                            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
                            <div class="image-preview" id="mainImagePreview"></div>
                        </div>
                        
                        <div class="search-box">
                            <label for="image_2">URL da Imagem 2 (opcional)</label>
                            <input type="text" id="image_2" name="image_2" value="<?php echo htmlspecialchars($product['image_2']); ?>">
                            <div class="image-preview" id="image2Preview"></div>
                        </div>
                        
                        <div class="search-box">
                            <label for="image_3">URL da Imagem 3 (opcional)</label>
                            <input type="text" id="image_3" name="image_3" value="<?php echo htmlspecialchars($product['image_3']); ?>">
                            <div class="image-preview" id="image3Preview"></div>
                        </div>
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="featured" <?php echo $product['featured'] ? 'checked' : ''; ?>>
                                <span>Produto em Destaque</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="description">Descrição *</label>
                            <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="filter-btn">Atualizar Produto</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
    <script>
        document.getElementById('image').addEventListener('input', function() {
            showImagePreview('image', 'mainImagePreview');
        });
        
        document.getElementById('image_2').addEventListener('input', function() {
            showImagePreview('image_2', 'image2Preview');
        });
        
        document.getElementById('image_3').addEventListener('input', function() {
            showImagePreview('image_3', 'image3Preview');
        });
        
        function showImagePreview(inputId, previewId) {
            const url = document.getElementById(inputId).value;
            const preview = document.getElementById(previewId);
            
            if(url) {
                preview.innerHTML = `<img src="${url}" alt="Preview">`;
            } else {
                preview.innerHTML = '';
            }
        }
        
        window.addEventListener('DOMContentLoaded', function() {
            showImagePreview('image', 'mainImagePreview');
            showImagePreview('image_2', 'image2Preview');
            showImagePreview('image_3', 'image3Preview');
        });
    </script>
</body>
</html>