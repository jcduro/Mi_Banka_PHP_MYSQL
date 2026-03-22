<?php include __DIR__ . '/../conexion.php'; ?>



    <div class="banka-container">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <a href="../index.php" class="banka-btn" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
           
    </div>

 

    
    <div class="banka-card">

        <h2>Nuevo Negocio</h2>

        <form method="POST" class="banka-form">
            <label>Nombre del Negocio:</label>
            <input type="text" name="name" required>

            <div style="margin-top: 20px;">
                <button type="submit" class="banka-btn banka-btn-primary">Guardar</button>
                <a href="list.php" class="banka-btn" style="background: rgba(128,128,128,0.2); color: inherit; margin-left: 10px;">Cancelar</a>
            </div>
        </form>

        <?php
if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO businesses (name) VALUES (?)");
    $stmt->execute([$_POST['name']]);
    echo "<script>window.location.href = 'list.php';</script>";
}
?>
    </div>
</div>
<?php include __DIR__ . '/../../templates/footer.php'; ?>
