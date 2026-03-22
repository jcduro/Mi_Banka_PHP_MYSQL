<?php include __DIR__ . '/../conexion.php'; ?>


    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
           
    </div>

 

    
    <div class="banka-card">

        <h2>Cuentas Bancarias</h2>
        <a href="create.php" class="banka-btn banka-btn-success" style="margin-bottom: 20px;">+ Nueva Cuenta</a>

        <div class="banka-table-wrapper">
            <table class="banka-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Negocio</th>
                        <th>Banco</th>
                        <th>Nº Cuenta</th>
                        <th>Titular</th>
                        <th>Moneda</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$query = "
                    SELECT 
                        ba.id, ba.account_number, ba.account_name, ba.currency,
                        b.name AS business_name, 
                        bk.name AS bank_name
                    FROM bank_accounts ba
                    LEFT JOIN businesses b ON ba.business_id = b.id
                    LEFT JOIN banks bk ON ba.bank_id = bk.id
                ";
$stmt = $pdo->query($query);

foreach ($stmt as $row) {
    echo "<tr>
                        <td data-label='ID'>{$row['id']}</td>
                        <td data-label='Negocio'>{$row['business_name']}</td>
                        <td data-label='Banco'>{$row['bank_name']}</td>
                        <td data-label='Nº Cuenta'>{$row['account_number']}</td>
                        <td data-label='Titular'>{$row['account_name']}</td>
                        <td data-label='Moneda' style='font-weight: bold;'>{$row['currency']}</td>
                        <td data-label='Acciones'>
                            <a href='edit.php?id={$row['id']}' class='banka-btn banka-btn-edit'>Editar</a>
                            <a href='delete.php?id={$row['id']}' class='banka-btn banka-btn-delete' onclick='return confirm(\"¿Deseas eliminar esta cuenta?\");'>Eliminar</a>
                        </td>
                    </tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
