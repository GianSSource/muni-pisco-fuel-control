<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Catalog;
use App\Models\Vehicle;

final class AdminController extends Controller
{
    private Catalog $catalog;
    private Vehicle $vehicle;

    public function __construct()
    {
        $this->catalog = new Catalog();
        $this->vehicle = new Vehicle();
    }

    public function catalogos(): void
    {
        $this->requireAdmin();

        $fuelTypes = $this->catalog->getByCategory('fuel_type');
        $areas = $this->catalog->getByCategory('area');
        $suppliers = $this->catalog->getByCategory('supplier');
        $vehicleTypes = $this->catalog->getByCategory('vehicle_type');
        $vehicles = $this->vehicle->getActive();

        $this->view('admin/catalogos', [
            'title' => 'Gestión de Catálogos y Parámetros',
            'fuelTypes' => $fuelTypes,
            'areas' => $areas,
            'suppliers' => $suppliers,
            'vehicleTypes' => $vehicleTypes,
            'vehicles' => $vehicles,
            'csrfToken' => $this->csrfToken(),
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function storeVehicle(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $unitCode = trim((string) ($_POST['unit_code'] ?? ''));
        $plate = strtoupper(trim((string) ($_POST['plate'] ?? '')));
        $areaId = ((int) ($_POST['area_id'] ?? 0)) ?: null;
        $vehicleTypeId = ((int) ($_POST['vehicle_type_id'] ?? 0)) ?: null;

        if ($unitCode === '' || $plate === '' || !$vehicleTypeId || !$areaId) {
            $this->redirect('admin/catalogos?error=Todos+los+campos+(codigo+de+unidad,+placa,+tipo+y+area)+son+obligatorios');
        }

        if (mb_strlen($unitCode) > 10) {
            $this->redirect('admin/catalogos?error=El+codigo+de+unidad+no+debe+superar+10+caracteres');
        }

        if (mb_strlen($plate) > 7) {
            $this->redirect('admin/catalogos?error=La+placa+no+debe+superar+7+caracteres');
        }

        if (!preg_match('/^[A-Za-z0-9\-]+$/', $unitCode) || !preg_match('/^[A-Za-z0-9\-]+$/', $plate)) {
            $this->redirect('admin/catalogos?error=Solo+se+permiten+letras,+numeros+y+guiones+(sin+espacios+ni+otros+simbolos)');
        }

        if ($this->vehicle->create($unitCode, $plate, $areaId, $vehicleTypeId)) {
            $this->redirect('admin/catalogos?success=Vehiculo+registrado+correctamente');
        } else {
            $this->redirect('admin/catalogos?error=No+se+pudo+registrar+el+vehiculo+(revisa+que+la+placa+y+codigo+no+esten+repetidos)');
        }
    }

    public function deleteVehicle(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('admin/catalogos?error=ID+invalido');
        }

        if ($this->vehicle->delete($id)) {
            $this->redirect('admin/catalogos?success=Vehiculo+eliminado+del+catalogo');
        } else {
            $this->redirect('admin/catalogos?error=Error+al+eliminar+el+vehiculo');
        }
    }

    public function storeCatalog(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $category = trim((string) ($_POST['category'] ?? ''));
        $name = trim((string) ($_POST['name'] ?? ''));

        $allowedCategories = ['fuel_type', 'area', 'supplier', 'vehicle_type'];
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
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) ($_POST['id'] ?? 0);
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
