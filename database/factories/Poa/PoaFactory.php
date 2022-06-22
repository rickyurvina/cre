<?php
namespace database\factories\Poa;

use App\Models\Poa\Poa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Poa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'year' => 2022,
            'name' => 'Nuevo Poa 2022',
            'user_id_in_charge' => 1,
            'company_id' => 1,
            'status' => 'EN PROCESO',
            'phase' => 'Planificacion',
            'reviewed' => false,
        ];
    }

    // $factory->define(App\Models\Poa\Poa::class, function (Faker\Generator $faker) {
    
    //     return [
    //         'year' => 2022,
    //         'name' => 'Nuevo Poa 2022',
    //         'user_id_in_charge' => 1,
    //         'company_id' => 1,
    //         'status' => 'EN PROCESO',
    //         'phase' => 'Planificacion',
    //         'reviewed' => false,
    //     ];
    // });
}