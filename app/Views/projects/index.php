<section class="page-head">
    <h1>Proyectos</h1>
    <div class="actions">
        <a class="button" href="<?= BASE_URL ?>proyectos/nuevo">Agregar</a>
        <a class="button secondary" href="<?= BASE_URL ?>reportes/proyectos">PDF</a>
    </div>
</section>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($projects as $project): ?>
            <tr>
                <td><?= htmlspecialchars($project['name']) ?></td>
                <td><?= htmlspecialchars($project['client_name']) ?></td>
                <td><?= htmlspecialchars($project['status']) ?></td>
                <td><?= htmlspecialchars((string) $project['start_date']) ?></td>
                <td><?= htmlspecialchars((string) $project['end_date']) ?></td>
                <td><a href="<?= BASE_URL ?>proyectos/editar?id=<?= (int) $project['id'] ?>">Editar</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>