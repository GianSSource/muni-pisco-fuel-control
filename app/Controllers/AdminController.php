<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Catalog;

final class AdminController extends Controller
{
    private Catalog $catalog;

    public function __construct()
    {
        $this->catalog = new Catalog();
    }

    public function catalogos(): void
    {
        $this->requireAuth();

        $fuelTypes = $this->catalog->getByCategory('fuel_type');
        $areas = $this->catalog->getByCategory('area');
        $suppliers = $this->catalog->getByCategory('supplier');

        $this->view('admin/catalogos', [
            'title' => 'Gestión de Catálogos y Parámetros',
            'fuelTypes' => $fuelTypes,
            'areas' => $areas,
            'suppliers' => $suppliers,
            'csrfToken' => $this->csrfToken(),
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function storeCatalog(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $category = trim((string) ($_POST['category'] ?? ''));
        $name = trim((string) ($_POST['name'] ?? ''));

        $allowedCategories = ['fuel_type', 'area', 'supplier'];
        if (!in_array($category, $allowedCategories, true) || $name === '') {
            $this->redirect('admin/catalogos?error=Ingrese+un+nombre+valido+y+seleccione+una+categoria');
        }

        if ($this->catalog->create($category, $name)) {
            $this->redirect('admin/catalogos?success=Elemento+agregado+correctamente+al+catalogo');
        } else {
            $this->redirect('admin/catalogos?error=Error+al+guardar+el+elemento+en+el+catalogo');
        }
    }

    public function deleteCatalog(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('admin/catalogos?error=ID+invalido');
        }

        if ($this->catalog->delete($id)) {
            $this->redirect('admin/catalogos?success=Elemento+eliminado+del+catalogo');
        } else {
            $this->redirect('admin/catalogos?error=Error+al+eliminar+el+elemento');
        }
    }
}
