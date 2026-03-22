<?php

require_once __DIR__ . 'conexion.php';

?>


    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
        
    </div>

    
    <div class="banka-card">
    
    <h2>Balances por Banco</h2>
      

    <div class="banka-actions-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <?php
// Query to get the total calculated balance for each bank
$sql = "
            SELECT 
                b.id, 
                b.name,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transactions t 
                    JOIN bank_accounts ba ON t.bank_account_id = ba.id 
                    WHERE ba.bank_id = b.id AND t.type = 'income'
                ) as total_income,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transactions t 
                    JOIN bank_accounts ba ON t.bank_account_id = ba.id 
                    WHERE ba.bank_id = b.id AND t.type = 'expense'
                ) as total_expense,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transfers tr
                    JOIN bank_accounts ba ON tr.to_account_id = ba.id
                    WHERE ba.bank_id = b.id
                ) as transfers_in,
                (
                    SELECT COALESCE(SUM(amount), 0) FROM transfers tr
                    JOIN bank_accounts ba ON tr.from_account_id = ba.id
                    WHERE ba.bank_id = b.id
                ) as transfers_out
            FROM banks b
        ";

$stmt = $pdo->query($sql);

// Array of colors and icons for varied look
$colors = ['#00d4ff', '#00ff41', '#ffb800', '#ff6b6b', '#9d00ff', '#ff00d4'];
$icons = ['mdi-bank', 'mdi-wallet', 'mdi-cash-multiple', 'mdi-safe', 'mdi-credit-card', 'mdi-coin'];
$i = 0;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Balance logic: Income - Expense + Transfers In - Transfers Out
    $balance = $row['total_income'] - $row['total_expense'] + $row['transfers_in'] - $row['transfers_out'];
    $color = $colors[$i % count($colors)];
    $icon = $icons[$i % count($icons)];
    $balanceClass = $balance >= 0 ? "banka-text-green" : "banka-text-red";
?>
            <!-- Wrapped in anchor tag to make it clickable -->
            <a href="bank_detail.php?bank_id=<?php echo $row['id']; ?>" class="banka-summary-card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 20px; text-align: center; border-top: 4px solid <?php echo $color; ?>;">
                <i class="mdi <?php echo $icon; ?>" style="color: <?php echo $color; ?>; font-size: 3em; margin-bottom: 15px; text-shadow: 0 0 10px <?php echo $color; ?>40;"></i>
                <h3 style="font-size: 1.2em; font-weight: 500; margin-bottom: 10px;"><?php echo htmlspecialchars($row['name']); ?></h3>
                
                <div class="amount <?php echo $balanceClass; ?>" style="font-size: 1.4em; font-weight: bold; margin-bottom: 15px;">
                    $<?php echo number_format($balance, 2); ?>
                </div>

                <div style="font-size: 0.8em; color: rgba(255,255,255,0.6); display: flex; flex-direction: column; gap: 5px; width: 100%; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Ingresos / Int.:</span>
                        <span style="color: #00ff41;">+$<?php echo number_format($row['total_income'], 2); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Gastos / Rete:</span>
                        <span style="color: #ff6b6b;">-$<?php echo number_format($row['total_expense'], 2); ?></span>
                    </div>
                </div>
            </a>
            <?php
    $i++;
}
?>
    </div>
</div>
</div>
<style>
/* Reusing card hover styles for nice user interaction */
.banka-summary-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.banka-summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.5);
}
</style>

