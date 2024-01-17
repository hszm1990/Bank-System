<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    private array $prefix = [
        912, 931, 932, 933, 901, 921, 919, 912, 913, 917,
        915, 916, 910, 939, 938, 937, 918, 914, 911, 934
    ];

    private array $firstName = [
        'عباس', 'وحید', 'علی', 'علیرضا', 'شاهرخ', 'سجاد', 'شایان', 'حسام', 'مهدی',
        'حسن', 'حمید', 'شقایق', 'المیرا', 'شیما', 'مرجان', 'پرستو', 'نگار', 'ترانه',
        'لیلا', 'مریم', 'سحر', 'زهرا', 'ریحانه', 'منیر', 'عطیه', 'پروانه', 'ترانه', 'آبتین', 'آرتین', 'سالار', 'یاشار', 'زامیاد', 'ساسان', 'سیاوش', 'ارغوان', 'بهنوش', 'پونه', 'المیرا', 'منا', 'مینا', 'زویا', 'شیما', 'شیرین', 'شهرزاد', 'میترا', 'مهسا', 'نگین', 'مرجان', 'نازنین', 'نیلوفر', 'نگار', 'یگانه', 'هستی', 'سیما', 'سروناز', 'ستاره', 'ساناز', 'رهام', 'رؤیا', 'تهمینه', 'پریرو', 'پگاه', 'پرتو', 'آندریا', 'مهرداد', 'مهزیار', 'مهرشاد', 'کورش', 'بهرام', 'بزرگمهر', 'اروند', 'اشکان', 'بابک', 'آرمان', 'آریانا', 'بیژن', 'بهروز', 'جهان', 'تورج', 'بهشاد', 'جمشید', 'جاوید', 'داریوش', 'احمد', 'محمود', 'محمد', 'پژمان'
    ];

    private array $lastName = [
        'نژاد', 'پور', 'زاده', 'راد'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->getFullName(),
            'mobile_number' =>
                $this->prefix[array_rand($this->prefix, 1)]
                . $this->faker->numberBetween(1111111, 9999999),
        ];
    }

    public function getFullName(): string
    {
        return $this->firstName[array_rand($this->firstName, 1)] .
            ' ' . $this->firstName[array_rand($this->firstName, 1)] .
            ' ' .  $this->lastName[array_rand($this->lastName, 1)];
    }
}
