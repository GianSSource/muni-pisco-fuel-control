<section class="page-head">
    <h1>Clientes</h1>
    <div class="actions">
        <a class="button" href="<?= BASE_URL ?>clientes/nuevos">Agregar</a>
        <a class="button secondary" href="<?= BASE_URL ?>reportes/clientes">PDF</a>
    </div>
</section>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['name']) ?></td>
                <td><?= htmlspecialchars($client['company'] ?? '') ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td><?= htmlspecialchars($client['phone'] ?? '') ?></td>
                <td><a href="<?= BASE_URL ?>clientes/editar?id=<?= (int) $client['id'] ?>">Editar</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>