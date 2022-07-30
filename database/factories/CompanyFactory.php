<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\Mime\MimeTypes;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Company $company) {
            if ($this->faker->boolean()) {
                $randFile = Arr::random(Storage::disk('local')->files('seeding'));
                $file = new UploadedFile(Storage::disk('local')->path($randFile), basename($randFile));

                if (!Storage::exists('logos')) {
                    Storage::makeDirectory('logos');
                }

                // update logo attribute
                $company->update([
                    'logo' => Storage::putFileAs(
                        'logos',
                        $file,
                        $file->hashName()
                    ),
                ]);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'email' => $this->faker->safeEmail(),
            'url' => $this->faker->url(),
            'invoice_address' => $this->faker->address(),
            'invoice_notes' => $this->faker->text(50),
        ];
    }
}
