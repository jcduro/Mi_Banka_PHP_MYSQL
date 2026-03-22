<?php include __DIR__ . 'conexion.php'; ?>


<?php
// Calculate Summary Data
$incomeQuery = $pdo->query("SELECT SUM(amount) as total FROM transactions WHERE type='income'");
$incomeTotal = $incomeQuery->fetchColumn() ?: 0;

$expenseQuery = $pdo->query("SELECT SUM(amount) as total FROM transactions WHERE type='expense'");
$expenseTotal = $expenseQuery->fetchColumn() ?: 0;

$balance = $incomeTotal - $expenseTotal;
$balanceClass = $balance >= 0 ? "banka-text-green" : "banka-text-red";
?>

<div class="banka-container">
    <h2 style="color: #00d4ff; margin-bottom: 20px; font-weight: 300;">Resumen Financiero</h2>

    <!-- Summary Cards -->
    <div class="banka-dashboard-grid">
        <div class="banka-summary-card">
            <i class="mdi mdi-cash-multiple icon" style="color: #00ff41;"></i>
            <h3>Ingresos Totales</h3>
            <div class="amount banka-text-green">$<?php echo number_format($incomeTotal, 2); ?></div>
        </div>
        
        <div class="banka-summary-card">
            <i class="mdi mdi-cart-outline icon" style="color: #ff6b6b;"></i>
            <h3>Gastos Totales</h3>
            <div class="amount banka-text-red">-$<?php echo number_format($expenseTotal, 2); ?></div>
        </div>
        
        <div class="banka-summary-card">
            <i class="mdi mdi-bank icon"></i>
            <h3>Balance General</h3>
            <div class="amount <?php echo $balanceClass; ?>">$<?php echo number_format($balance, 2); ?></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h3 style="color: #00d4ff; margin-top: 10px; margin-bottom: 20px; font-weight: 300;">Acceso Rápido</h3>
    <div class="banka-actions-grid">
        <a href="bank_balances.php" class="banka-action-btn">
            <i class="mdi mdi-scale-balance" style="color: #ffb800;"></i>
            <span>Saldos por Banco</span>
        </a>
        <a href="transactions/create.php" class="banka-action-btn">
            <i class="mdi mdi-plus-circle" style="color: #00ff41;"></i>
            <span>Nuevo Movimiento</span>
        </a>
        <a href="transfers/create.php" class="banka-action-btn">
            <i class="mdi mdi-swap-horizontal"></i>
            <span>Transferir</span>
        </a>
        <a href="bank_accounts/list.php" class="banka-action-btn">
            <i class="mdi mdi-account-card-details"></i>
            <span>Cuentas Bancarias</span>
        </a>
        <a href="categories/list.php" class="banka-action-btn">
            <i class="mdi mdi-tag-multiple"></i>
            <span>Categorías</span>
        </a>
        <a href="businesses/list.php" class="banka-action-btn">
            <i class="mdi mdi-domain"></i>
            <span>Negocios</span>
        </a>
    </div>

    <!-- Recent Transactions -->
    <div class="banka-card">
        <h3>Últimos Movimientos</h3>
        <div class="banka-table-wrapper">
            <table class="banka-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cuenta</th>
                        <th>Categoría</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$recentQuery = "
                    SELECT 
                        t.transaction_date, t.type, t.amount,
                        ba.account_name, ba.currency,
                        c.name AS category_name
                    FROM transactions t
                    LEFT JOIN bank_accounts ba ON t.bank_account_id = ba.id
                    LEFT JOIN categories c ON t.category_id = c.id
                    ORDER BY t.transaction_date DESC, t.id DESC
                    LIMIT 5
                ";
$stmt = $pdo->query($recentQuery);

if ($stmt->rowCount() > 0) {
    foreach ($stmt as $row) {
        $monto = number_format($row['amount'], 2);
        $claseMonto = $row['type'] == 'income' ? 'banka-text-green' : 'banka-text-red';
        $signo = $row['type'] == 'income' ? '+' : '-';

        echo "<tr>
                            <td data-label='Fecha'>{$row['transaction_date']}</td>
                            <td data-label='Cuenta'>{$row['account_name']}</td>
                            <td data-label='Categoría'>{$row['category_name']}</td>
                            <td data-label='Monto' class='{$claseMonto}'>{$signo} {$row['currency']} {$monto}</td>
                        </tr>";
    }
}
else {
    echo "<tr><td colspan='4' style='text-align:center;'>No hay movimientos recientes.</td></tr>";
}
?>
                </tbody>
            </table>
        </div>
        <div style="text-align: right; margin-top: 15px;">
            <a href="transactions/list.php" class="banka-btn banka-btn-edit">Ver todos los movimientos &rarr;</a>
        </div>
    </div>
</div>
