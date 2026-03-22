<?php include __DIR__ . '/../conexion.php'; ?>


<?php
if (!isset($_GET['id'])) {
    die("ID no proporcionado");
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Categoría no encontrada");
}

if ($_POST) {
    $updateStmt = $pdo->prepare("UPDATE categories SET name = ?, type = ? WHERE id = ?");
    $updateStmt->execute([
        $_POST['name'],
        $_POST['type'],
        $id
    ]);
    echo "<script>window.location.href = 'list.php';</script>";
    exit;
}
?>

<div class="banka-container">
    <div class="banka-card">
        <h2>Editar Categoría</h2>

        <form method="POST" class="banka-form">
            <label>Nombre:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>

            <label>Tipo:</label>
            <select name="type" required>
                <option value="income" <?php echo($category['type'] == 'income') ? 'selected' : ''; ?>>Ingreso</option>
                <option value="expense" <?php echo($category['type'] == 'expense') ? 'selected' : ''; ?>>Gasto</option>
            </select>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Actualizar Categoría</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
