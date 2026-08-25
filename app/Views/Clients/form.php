<?php $editing = is_array($client); ?>
<section class="page-head">
    <h1><?= $editing ? 'Editar cliente' : 'Nuevo cliente' ?></h1>
</section>

<form method="post" action="<?= BASE_URL . ($editing ? 'clientes/actualizar' : 'clientes/guardar') ?>" class="form_wide">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $client['id'] ?>"><?php endif; ?>

    <label>Nombre
        <input type="text" name="name" required value="<?= htmlspecialchars($client['name'] ?? '') ?>">
    </label>
    <label>Empresa
        <input type="text" name="company" value="<?= htmlspecialchars($client['company'] ?? '') ?>">
    </label>
    <label>Email
        <input type="email" name="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>">
    </label>
    <label>Telefono
        <input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>">
    </label>
    <label>Direccion
        <textarea name="address"><?= htmlspecialchars($client['address'] ?? '') ?></textarea>
    </label>
    <button type="submit">Guardar</button>
</form>