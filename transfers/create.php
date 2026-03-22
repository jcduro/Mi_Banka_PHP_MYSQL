<?php

require_once __DIR__ . '/../conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['from_account_id'] == $_POST['to_account_id']) {
        $error_msg = "La cuenta de origen y destino no pueden ser la misma.";
    }
    else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO transfers (from_account_id, to_account_id, amount, description, transfer_date)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['from_account_id'],
                $_POST['to_account_id'],
                $_POST['amount'],
                $_POST['description'],
                $_POST['transfer_date']
            ]);
            header("Location: list.php");
            exit;
        }
        catch (Exception $e) {
            $error_msg = "Error al guardar la transferencia: " . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/../../templates/header.php';

require_once __DIR__ . '/../../templates/menu.php';

?>

    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>

    </div>

         <div style="text-align: right; margin-top: 15px;">
            <a href="list.php" class="banka-btn banka-btn-edit">Ver todas las transferencias &rarr;</a>
        </div>
<p>&nbsp;</p>
    
    <div class="banka-card">

        <h2>Nueva Transferencia</h2>

        <?php if (isset($error_msg)): ?>
            <p style='color: #ff6b6b; margin-bottom: 15px;'><?php echo $error_msg; ?></p>
        <?php
endif; ?>

        <form method="POST" class="banka-form">
            <label>Desde la Cuenta (Origen):</label>
            <select name="from_account_id" required>
                <option value="">Seleccione cuenta de origen...</option>
            <?php
$res = $pdo->query("SELECT ba.id, ba.account_name, ba.account_number, b.name AS biz_name 
                                FROM bank_accounts ba 
                                LEFT JOIN businesses b ON ba.business_id = b.id");
$accounts = $res->fetchAll(PDO::FETCH_ASSOC);
foreach ($accounts as $acc) {
    echo "<option value='{$acc['id']}'>{$acc['biz_name']} - {$acc['account_name']} ({$acc['account_number']})</option>";
}
?>
            </select>

            <label>Hacia la Cuenta (Destino):</label>
            <select name="to_account_id" required>
                <option value="">Seleccione cuenta de destino...</option>
            <?php
foreach ($accounts as $acc) {
    echo "<option value='{$acc['id']}'>{$acc['biz_name']} - {$acc['account_name']} ({$acc['account_number']})</option>";
}
?>
            </select>

            <label>Monto a transferir:</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Descripción:</label>
            <input type="text" name="description">

            <label>Fecha de Transferencia:</label>
            <input type="date" name="transfer_date" value="<?php echo date('Y-m-d'); ?>" required>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Guardar Transferencia</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>

    </div>
</div>

