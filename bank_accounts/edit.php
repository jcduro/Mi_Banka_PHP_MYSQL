<?php include __DIR__ . '/../conexion.php'; ?>


<?php
if (!isset($_GET['id'])) {
    die("ID no proporcionado");
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM bank_accounts WHERE id = ?");
$stmt->execute([$id]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$account) {
    die("Cuenta no encontrada");
}

if ($_POST) {
    $updateStmt = $pdo->prepare("UPDATE bank_accounts SET business_id = ?, bank_id = ?, account_number = ?, account_name = ?, currency = ? WHERE id = ?");
    $updateStmt->execute([
        $_POST['business_id'],
        $_POST['bank_id'],
        $_POST['account_number'],
        $_POST['account_name'],
        $_POST['currency'],
        $id
    ]);
    echo "<script>window.location.href = 'list.php';</script>";
    exit;
}
?>

<div class="banka-container">
    <div class="banka-card">
        <h2>Editar Cuenta Bancaria</h2>

        <form method="POST" class="banka-form">
            <label>Negocio:</label>
            <select name="business_id" required>
            <?php
$bStmt = $pdo->query("SELECT * FROM businesses");
foreach ($bStmt as $row) {
    $selected = ($row['id'] == $account['business_id']) ? 'selected' : '';
    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
}
?>
            </select>

            <label>Banco:</label>
            <select name="bank_id" required>
            <?php
$bkStmt = $pdo->query("SELECT * FROM banks");
foreach ($bkStmt as $row) {
    $selected = ($row['id'] == $account['bank_id']) ? 'selected' : '';
    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
}
?>
            </select>

            <label>Número de Cuenta:</label>
            <input type="text" name="account_number" value="<?php echo htmlspecialchars($account['account_number']); ?>" required>

            <label>Nombre Titular:</label>
            <input type="text" name="account_name" value="<?php echo htmlspecialchars($account['account_name']); ?>" required>

            <label>Moneda:</label>
            <select name="currency" required>
                <option value="COP" <?php echo($account['currency'] == 'COP') ? 'selected' : ''; ?>>COP (Pesos Colombianos)</option>
                <option value="USD" <?php echo($account['currency'] == 'USD') ? 'selected' : ''; ?>>USD (Dólares)</option>
                <option value="EUR" <?php echo($account['currency'] == 'EUR') ? 'selected' : ''; ?>>EUR (Euros)</option>
            </select>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Actualizar Cuenta</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
