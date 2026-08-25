<?php $editing = is_array($ingreso ?? null); ?>

<style>
    .center-container {
        max-width: 650px;
        margin: 40px auto;
    }

    .page-head h1 {
        font-family: system-ui, -apple-system, sans-serif;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .form_wide {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); 
        border: 1px solid #e2e8f0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .form_wide label {
        display: flex;
        flex-direction: column;
        font-weight: 600;
        font-size: 14px;
        color: #334155;
        gap: 6px; 
    }

    .form_wide .full-width {
        grid-column: 1 / -1;
    }

    .form_wide input,
    .form_wide textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
    }

    .form_wide input:focus,
    .form_wide textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form_wide textarea {
        min-height: 80px;
        resize: vertical;
    }

    .botones-container {
        grid-column: 1 / -1;
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .btn-guardar {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 10px 28px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-guardar:hover {
        background-color: #1d4ed8;
    }

    .btn-atras {
        display: inline-flex;
        align-items: center;
        background-color: #f1f5f9;
        color: #475569;
        text-decoration: none;
        padding: 10px 28px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 15px;
        border: 1px solid #cbd5e1;
        transition: all 0.2s;
    }

    .btn-atras:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }
</style>

<div class="center-container">
    <section class="page-head">
        <h1><?= $editing ? 'Editar ingreso' : 'Nuevo ingreso' ?></h1>
    </section>

    <form method="post" action="<?= BASE_URL . ($editing ? 'ingresos/actualizar' : 'ingresos/guardar') ?>" class="form_wide">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $ingreso['id'] ?>"><?php endif; ?>

        <label>Nombre
            <input type="text" name="name" required value="<?= htmlspecialchars($ingreso['name'] ?? '') ?>">
        </label>
        
        <label>Empresa
            <input type="text" name="company" value="<?= htmlspecialchars($ingreso['company'] ?? '') ?>">
        </label>
        
        <label>Email
            <input type="email" name="email" value="<?= htmlspecialchars($ingreso['email'] ?? '') ?>">
        </label>
        
        <label>Telefono
            <input type="text" name="phone" value="<?= htmlspecialchars($ingreso['phone'] ?? '') ?>">
        </label>
        
        <label class="full-width">Direccion
            <textarea name="address"><?= htmlspecialchars($ingreso['address'] ?? '') ?></textarea>
        </label>
        
        <div class="botones-container">
            <button type="submit" class="btn-guardar">Guardar</button>
            <!-- Conectado directo al dashboard -->
            <a href="<?= BASE_URL ?>dashboard" class="btn-atras">Atrás</a>
        </div>
    </form>
</div>