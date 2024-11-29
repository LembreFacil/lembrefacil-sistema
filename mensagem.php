<?php if (isset($_SESSION['mensagem'])): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($_SESSION['mensagem']) ?>
    </div>
    <?php unset($_SESSION['mensagem']); ?> <!-- Limpa a mensagem após exibição -->
<?php endif; ?>
