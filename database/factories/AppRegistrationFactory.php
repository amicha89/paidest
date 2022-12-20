<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppRegistrationFactory extends Factory
{
    

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $faker->dateTimeBetween('1990-01-01', '2012-12-31')->format('d/m/Y')
        $status = $this->faker->randomElement([0, 1]);
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'dob' => $this->faker->dateTimeBetween('1990-01-01', '2012-12-31')->format('d/m/Y'),
            'rule' => 'CEO',
            'company_name' => $this->faker->text(10),
            'company_number' => $this->faker->phoneNumber,
            'company_type' => $this->faker->text(10),
            'companyIndustry' => $this->faker->text(10),
            'registeredCountry' => $this->faker->countryCode,
            'source_of_funds' => $this->faker->text(10),
            'streetAddress' => $this->faker->streetAddress,
            'cityState' => $this->faker->city,
            'zipCode' => $this->faker->randomNumber,
            'ipAddress' => $this->faker->ipv4,
            'status' => $status,
            'dateTime' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            
        ];
    }
}
