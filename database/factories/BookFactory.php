<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3,true),
            'isbn' => $this->faker->isbn10(),
            'published_at' => $this->faker->dateTimeBetween('1970-01-01', '2021-02-20')->format('Y/m/d'),
            'status' => 'AVAILABLE',
        ];
    }
}
