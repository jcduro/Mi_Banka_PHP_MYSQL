<?php include __DIR__ . '/../conexion.php'; ?>


    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
           
    </div>

 

    
    <div class="banka-card">

        <h2>Negocios</h2>
        <a href="create.php" class="banka-btn banka-btn-success" style="margin-bottom: 20px;">+ Nuevo Negocio</a>

        <div class="banka-table-wrapper">
            <table class="banka-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$stmt = $pdo->query("SELECT * FROM businesses");
foreach ($stmt as $row) {
    echo "<tr>
                        <td data-label='ID'>{$row['id']}</td>
                        <td data-label='Nombre'>{$row['name']}</td>
                        <td data-label='Acciones'>
                            <a href='edit.php?id={$row['id']}' class='banka-btn banka-btn-edit'>Editar</a>
                            <a href='delete.php?id={$row['id']}' class='banka-btn banka-btn-delete' onclick='return confirm(\"¿Eliminar negocio?\");'>Eliminar</a>
                        </td>
                    </tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
