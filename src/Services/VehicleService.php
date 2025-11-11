<?php
// src/Services/VehicleService.php
//path for the Vehicle Service
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../Models/Vehicle.php";

class VehicleService {
    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getAll(): array {
        $result = $this->conn->query("SELECT * FROM vehicles");
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = new Vehicle($row);
        }
        return $vehicles;
    }

    public function add(Vehicle $vehicle): bool {
        $stmt = $this->conn->prepare("INSERT INTO vehicles (make, model, year, price_per_day, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $vehicle->make, $vehicle->model, $vehicle->year, $vehicle->price_per_day, $vehicle->status);
        return $stmt->execute();
    }

    public function update(Vehicle $vehicle): bool {
        $stmt = $this->conn->prepare("UPDATE vehicles SET make=?, model=?, year=?, price_per_day=?, status=? WHERE id=?");
        $stmt->bind_param("ssidsi", $vehicle->make, $vehicle->model, $vehicle->year, $vehicle->price_per_day, $vehicle->status, $vehicle->id);
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM vehicles WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
