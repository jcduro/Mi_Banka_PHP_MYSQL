<?php
require_once __DIR__ . 'conexion.php';

$bank_id = isset($_GET['bank_id']) ? (int)$_GET['bank_id'] : 0;

// Get bank name for the title
$bankNameStmt = $pdo->prepare("SELECT name FROM banks WHERE id = ?");
$bankNameStmt->execute([$bank_id]);
$bankName = $bankNameStmt->fetchColumn();

if (!$bankName) {
    echo "<div class='banka-container'><h2>Banco no encontrado</h2></div>";
    include __DIR__ . '/../templates/footer.php';
    exit;
}
?>



        <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="bank_balances.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
        
    </div>

    
    <div class="banka-card">
    
     <h2 style="color: #00d4ff; font-weight: 300; margin: 0;">Detalle: <?php echo htmlspecialchars($bankName); ?></h2>

    

    <div class="banka-actions-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <?php
// Query to get the total calculated balance for each individual bank account in this bank
$sql = "
            SELECT 
                ba.id, 
                ba.account_name,
                ba.account_number,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transactions t 
                    WHERE t.bank_account_id = ba.id AND t.type = 'income'
                ) as total_income,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transactions t 
                    WHERE t.bank_account_id = ba.id AND t.type = 'expense'
                ) as total_expense,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transfers tr
                    WHERE tr.to_account_id = ba.id
                ) as transfers_in,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transfers tr
                    WHERE tr.from_account_id = ba.id
                ) as transfers_out
            FROM bank_accounts ba
            WHERE ba.bank_id = ?
        ";

$stmt = $pdo->prepare($sql);
$stmt->execute([$bank_id]);

$colors = ['#ff8a00', '#00e5ff', '#e500ff', '#00ff41', '#ffb800', '#ff6b6b'];
$icons = ['mdi-account-circle', 'mdi-wallet-membership', 'mdi-account-card-details', 'mdi-contactless-payment', 'mdi-identifier', 'mdi-shield-account'];
$i = 0;

$hasAccounts = false;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hasAccounts = true;
    $balance = $row['total_income'] - $row['total_expense'] + $row['transfers_in'] - $row['transfers_out'];
    $color = $colors[$i % count($colors)];
    $icon = $icons[$i % count($icons)];
    $balanceClass = $balance >= 0 ? "banka-text-green" : "banka-text-red";

    // Format account number elegantly
    $accNumber = !empty($row['account_number']) ? '***' . substr($row['account_number'], -4) : 'N/A';
?>
            <div class="banka-summary-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 20px; text-align: center; border-top: 4px solid <?php echo $color; ?>; background: rgba(0,0,0,0.2);">
                <i class="mdi <?php echo $icon; ?>" style="color: <?php echo $color; ?>; font-size: 2.5em; margin-bottom: 15px;"></i>
                
                <h3 style="font-size: 1.1em; font-weight: 500; margin-bottom: 5px; color: #fff;"><?php echo htmlspecialchars($row['account_name']); ?></h3>
                <span style="font-size: 0.75em; color: #888; margin-bottom: 10px;">ID Cta: <?php echo $accNumber; ?></span>
                
                <div class="amount <?php echo $balanceClass; ?>" style="font-size: 1.6em; font-weight: bold; margin-bottom: 15px;">
                    $<?php echo number_format($balance, 2); ?>
                </div>

                <div style="font-size: 0.8em; color: rgba(255,255,255,0.6); display: flex; flex-direction: column; gap: 5px; width: 100%; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Entradas:</span>
                        <span style="color: #00ff41;">+$<?php echo number_format($row['total_income'] + $row['transfers_in'], 2); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Salidas:</span>
                        <span style="color: #ff6b6b;">-$<?php echo number_format($row['total_expense'] + $row['transfers_out'], 2); ?></span>
                    </div>
                </div>
                
                <a href="transactions/list.php?bank_account_id=<?php echo $row['id']; ?>" class="banka-btn" style="margin-top: 15px; font-size: 0.8em; padding: 5px 15px; background: rgba(255,255,255,0.05); color: #fff;">Ver Movimientos</a>
            </div>
            <?php
    $i++;
}

if (!$hasAccounts) {
    echo "<div style='grid-column: 1 / -1; text-align: center; color: #888; padding: 40px;'>No hay cuentas registradas en este banco.</div>";
}
?>
    </div>
</div>

<style>
/* Reusing card hover styles */
.banka-summary-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.banka-summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.5);
}
</style>
