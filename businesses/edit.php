<?php
include __DIR__ . '/../conexion.php';



if (!isset($_GET['id'])) {
    die("ID no proporcionado");
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM businesses WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Negocio no encontrado");
}

if ($_POST) {
    $updateStmt = $pdo->prepare("UPDATE businesses SET name = ? WHERE id = ?");
    $updateStmt->execute([$_POST['name'], $id]);
    echo "<script>window.location.href = 'list.php';</script>";
    exit;
}
?>

<div class="banka-container">
    <div class="banka-card">
        <h2>Editar Negocio</h2>

        <form method="POST" class="banka-form">
            <label>Nombre del Negocio:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Actualizar</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
