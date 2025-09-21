<?php
class Vehicle {
    // Properties (attributes)
    private string $make;
    private string $model;
    private int $year;

    // Constructor
    public function __construct(string $make, string $model, int $year) {
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
    }

    // Getters
    public function getMake(): string {
        return $this->make;
    }

    public function getModel(): string {
        return $this->model;
    }

    public function getYear(): int {
        return $this->year;
    }

    // Setters
    public function setMake(string $make): void {
        $this->make = $make;
    }

    public function setModel(string $model): void {
        $this->model = $model;
    }

    public function setYear(int $year): void {
        $this->year = $year;
    }

    // Example behavior (method)
    public function getVehicleInfo(): string {
        return $this->year . " " . $this->make . " " . $this->model;
    }
}
