<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Property>
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->randomElement([
                'Dhanmondi Family Apartment',
                'Uttara Rental Flat',
                'Purbachal Residential Plot',
            ]),
            'purpose' => $this->faker->randomElement(['sale', 'rent']),
            'property_type' => $this->faker->randomElement(['Apartment', 'House', 'Land']),
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'availability_status' => 'available',
            'price' => $this->faker->numberBetween(35000, 8500000),
            'area_size' => $this->faker->randomFloat(2, 650, 3200),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'garages' => $this->faker->numberBetween(0, 2),
            'location' => $this->faker->randomElement(['Dhanmondi, Dhaka', 'Uttara, Dhaka', 'Sylhet Sadar, Sylhet']),
            'district' => $this->faker->randomElement(['Dhaka', 'Sylhet']),
            'division' => $this->faker->randomElement(['Dhaka', 'Sylhet']),
            'postal_code' => $this->faker->randomElement(['1209', '1212', '3100']),
            'address' => $this->faker->address(),
            'description' => $this->faker->paragraph(),
            'contact_phone' => '01'.$this->faker->numerify('#########'),
        ];
    }
}
