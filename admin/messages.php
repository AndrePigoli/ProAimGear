<?php
session_start();
include '../includes/db-connect.php';
include '../includes/functions.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_filter = isset($_GET['date']) ? trim($_GET['date']) : '';

$query = "SELECT * FROM contact_messages";
$where_clauses = [];

if (!empty($search_term)) {
    $search_term = mysqli_real_escape_string($conn, $search_term);
    $where_clauses[] = "(name LIKE '%$search_term%' OR email LIKE '%$search_term%' OR subject LIKE '%$search_term%' OR message LIKE '%$search_term%')";
}

if (!empty($date_filter)) {
    $date_filter = mysqli_real_escape_string($conn, $date_filter);
    $where_clauses[] = "DATE(created_at) = '$date_filter'";
}

if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(' AND ', $where_clauses);
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Mensagens - ProAim Gear Admin</title>
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
                <h1>Gerenciar Mensagens</h1>
            </div>
            
            <div class="filter-container">
                <form action="" method="get" class="filter-form">
                    <div class="search-box">
                        <div class="input-icon">
                            <input type="text" name="search" placeholder="Pesquisar por nome, e-mail, assunto ou mensagem " value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                    </div>
                    <div class="filter-box">
                        <input type="date" name="date"  class="date" value="<?php echo htmlspecialchars($date_filter); ?>">
                        <button type="submit" class="filter-btn">Filtrar</button>
                        <?php if (!empty($search_term) || !empty($date_filter)): ?>
                            <a href="messages.php" class="reset-btn">Limpar filtros</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Todas as Mensagens</h2>
                </div>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Assunto</th>
                                <th>Mensagem</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($result) > 0) {
                                while ($message = mysqli_fetch_assoc($result)) {
                                    $date = date('d/m/Y H:i', strtotime($message['created_at']));
                                    
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($message['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($message['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($message['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($message['subject']) . "</td>";
                                    echo "<td class='message-content'>" . htmlspecialchars($message['message']) . "</td>";
                                    echo "<td>" . htmlspecialchars($date) . "</td>";
                                    echo "<td>
                                            <a href='view-message.php?id={$message['id']}' class='action-btn view'><i class='fas fa-eye'></i></a>
                                            <a href='delete-message.php?id={$message['id']}' class='action-btn delete' onclick='return confirm(\"Tem certeza que deseja excluir esta mensagem?\")'><i class='fas fa-trash-alt'></i></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='no-data'>Nenhuma mensagem encontrada</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>