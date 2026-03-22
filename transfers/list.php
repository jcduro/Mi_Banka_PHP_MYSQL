<?php include __DIR__ . '/../conexion.php'; ?>


    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
         <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
           
    </div>

 

    
    <div class="banka-card">

        <h2>Transferencias</h2>
        <a href="create.php" class="banka-btn banka-btn-success" style="margin-bottom: 20px;">+ Nueva Transferencia</a>

        <div class="banka-table-wrapper">
            <table class="banka-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Desde la Cuenta</th>
                        <th>Hacia la Cuenta</th>
                        <th>Monto</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$query = "
                    SELECT 
                        t.id, t.transfer_date, t.amount, t.description,
                        ba_from.account_name AS from_name, ba_from.account_number AS from_number, ba_from.currency AS from_cur,
                        ba_to.account_name AS to_name, ba_to.account_number AS to_number, ba_to.currency AS to_cur
                    FROM transfers t
                    LEFT JOIN bank_accounts ba_from ON t.from_account_id = ba_from.id
                    LEFT JOIN bank_accounts ba_to ON t.to_account_id = ba_to.id
                    ORDER BY t.transfer_date DESC, t.id DESC
                ";
$stmt = $pdo->query($query);

foreach ($stmt as $row) {
    echo "<tr>
                        <td data-label='ID'>{$row['id']}</td>
                        <td data-label='Fecha'>{$row['transfer_date']}</td>
                        <td data-label='Desde la Cuenta'>{$row['from_name']} ({$row['from_number']})</td>
                        <td data-label='Hacia la Cuenta'>{$row['to_name']} ({$row['to_number']})</td>
                        <td data-label='Monto' style='font-weight: bold;'>{$row['from_cur']} " . number_format($row['amount'], 2) . "</td>
                        <td data-label='Descripción'>{$row['description']}</td>
                        <td data-label='Acciones'>
                            <a href='edit.php?id={$row['id']}' class='banka-btn banka-btn-edit'>Editar</a>
                            <a href='delete.php?id={$row['id']}' class='banka-btn banka-btn-delete' onclick='return confirm(\"¿Deseas eliminar esta transferencia?\");'>Eliminar</a>
                        </td>
                    </tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>

