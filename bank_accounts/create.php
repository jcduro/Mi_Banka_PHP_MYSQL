<?php include __DIR__ . '/../conexion.php'; ?>


    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
           
    </div>

 

    
    <div class="banka-card">
        
        <h2>Nueva Cuenta Bancaria</h2>

        <form method="POST" class="banka-form">
            <label>Negocio:</label>
            <select name="business_id" required>
            <?php
$stmt = $pdo->query("SELECT * FROM businesses");
foreach ($stmt as $row) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>
            </select>

            <label>Banco:</label>
            <select name="bank_id" required>
            <?php
$stmt = $pdo->query("SELECT * FROM banks");
foreach ($stmt as $row) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>
            </select>

            <label>Número de Cuenta:</label>
            <input type="text" name="account_number" required>

            <label>Nombre Titular:</label>
            <input type="text" name="account_name" required>

            <label>Moneda:</label>
            <select name="currency" required>
                <option value="COP">COP (Pesos Colombianos)</option>
                <option value="USD">USD (Dólares)</option>
                <option value="EUR">EUR (Euros)</option>
            </select>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Guardar Cuenta</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>

        <?php
if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO bank_accounts (business_id, bank_id, account_number, account_name, currency) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['business_id'],
        $_POST['bank_id'],
        $_POST['account_number'],
        $_POST['account_name'],
        $_POST['currency']
    ]);
    echo "<script>window.location.href = 'list.php';</script>";
}
?>
    </div>
</div>
