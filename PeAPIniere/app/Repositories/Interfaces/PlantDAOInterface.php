<?php 
namespace App\Repositories\Interfaces;

use App\Models\Plant;

interface PlantDAOInterface
{
    public function getAllPlants();
    public function getPlantBySlug(string $slug);
    public function createPlant(array $data);
    public function updatePlant(Plant $plant, array $data);
    public function deletePlant(Plant $plant);
}
