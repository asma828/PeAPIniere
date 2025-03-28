<?php
namespace App\Repositories;

use App\Models\Plant;
use App\Repositories\Interfaces\PlantDAOInterface;

class PlantDAO implements PlantDAOInterface
{
    public function getAllPlants()
    {
        return Plant::all();
    }

    public function getPlantBySlug(string $slug)
    {
        return Plant::where('slug', $slug)->firstOrFail();
    }

    public function createPlant(array $data)
    {
        return Plant::create($data);
    }

    public function updatePlant(Plant $plant, array $data)
    {
        $plant->update($data);
        return $plant;
    }

    public function deletePlant(Plant $plant)
    {
        return $plant->delete();
    }
}
