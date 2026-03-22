<?php include __DIR__ . '/../conexion.php'; ?>


    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
           
    </div>

 

    
    <div class="banka-card">
        
        <h2>Nueva Categoría</h2>

        <form method="POST" class="banka-form">
            <label>Nombre:</label>
            <input type="text" name="name" required>

            <label>Tipo:</label>
            <select name="type" required>
                <option value="income">Ingreso</option>
                <option value="expense">Gasto</option>
            </select>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Guardar Categoría</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>

        <?php
if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO categories (name, type) VALUES (?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['type']
    ]);
    echo "<script>window.location.href = 'list.php';</script>";
}
?>
    </div>
</div>

