<?php include __DIR__ . '/../conexion.php'; ?>


<?php
if (!isset($_GET['id'])) {
    die("ID no proporcionado");
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM transfers WHERE id = ?");
$stmt->execute([$id]);
$transfer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transfer) {
    die("Transferencia no encontrada");
}

$res = $pdo->query("SELECT ba.id, ba.account_name, ba.account_number, b.name AS biz_name 
                    FROM bank_accounts ba 
                    LEFT JOIN businesses b ON ba.business_id = b.id");
$accounts = $res->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    if ($_POST['from_account_id'] == $_POST['to_account_id']) {
        $error = "La cuenta de origen y destino no pueden ser la misma.";
    }
    else {
        $updateStmt = $pdo->prepare("UPDATE transfers SET from_account_id = ?, to_account_id = ?, amount = ?, description = ?, transfer_date = ? WHERE id = ?");
        $updateStmt->execute([
            $_POST['from_account_id'],
            $_POST['to_account_id'],
            $_POST['amount'],
            $_POST['description'],
            $_POST['transfer_date'],
            $id
        ]);
        echo "<script>window.location.href = 'list.php';</script>";
        exit;
    }
}
?>

<div class="banka-container">
    <div class="banka-card">
        <h2>Editar Transferencia</h2>

        <?php if (isset($error))
    echo "<p style='color:#ff6b6b; padding:10px; background:rgba(255, 107, 107, 0.1); border:1px solid #ff6b6b; border-radius:5px;'>$error</p>"; ?>

        <form method="POST" class="banka-form">
            <label>Desde la Cuenta (Origen):</label>
            <select name="from_account_id" required>
            <?php
foreach ($accounts as $acc) {
    $sel = $acc['id'] == $transfer['from_account_id'] ? 'selected' : '';
    echo "<option value='{$acc['id']}' $sel>{$acc['biz_name']} - {$acc['account_name']} ({$acc['account_number']})</option>";
}
?>
            </select>

            <label>Hacia la Cuenta (Destino):</label>
            <select name="to_account_id" required>
            <?php
foreach ($accounts as $acc) {
    $sel = $acc['id'] == $transfer['to_account_id'] ? 'selected' : '';
    echo "<option value='{$acc['id']}' $sel>{$acc['biz_name']} - {$acc['account_name']} ({$acc['account_number']})</option>";
}
?>
            </select>

            <label>Monto a transferir:</label>
            <input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($transfer['amount']); ?>" required>

            <label>Descripción:</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($transfer['description']); ?>">

            <label>Fecha de Transferencia:</label>
            <input type="date" name="transfer_date" value="<?php echo htmlspecialchars($transfer['transfer_date']); ?>" required>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Actualizar Transferencia</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
