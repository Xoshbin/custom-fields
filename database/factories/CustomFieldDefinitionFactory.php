<?php

namespace Xoshbin\CustomFields\Database\Factories;

use Xoshbin\CustomFields\Enums\CustomFieldType;
use Xoshbin\CustomFields\Models\CustomFieldDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Xoshbin\CustomFields\Models\CustomFieldDefinition>
 */
class CustomFieldDefinitionFactory extends Factory
{
    protected $model = CustomFieldDefinition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => 'App\\Models\\Partner',
            'name' => [
                'en' => $this->faker->words(3, true) . ' Custom Fields',
                'ar' => 'حقول مخصصة ' . $this->faker->word(),
                'ckb' => 'خانەی تایبەت ' . $this->faker->word(),
            ],
            'description' => [
                'en' => $this->faker->sentence(),
                'ar' => 'وصف للحقول المخصصة',
                'ckb' => 'پەسەندکردنی خانەی تایبەت',
            ],
            'field_definitions' => [
                [
                    'key' => 'sample_text',
                    'label' => [
                        'en' => 'Sample Text Field',
                        'ar' => 'حقل نص تجريبي',
                        'ckb' => 'خانەی نووسین',
                    ],
                    'type' => CustomFieldType::Text->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
                [
                    'key' => 'sample_select',
                    'label' => [
                        'en' => 'Sample Select Field',
                        'ar' => 'حقل اختيار تجريبي',
                        'ckb' => 'خانەی هەڵبژاردن',
                    ],
                    'type' => CustomFieldType::Select->value,
                    'required' => false,
                    'show_in_table' => false,
                    'options' => [
                        [
                            'value' => 'option1',
                            'label' => [
                                'en' => 'Option 1',
                                'ar' => 'الخيار 1',
                                'ckb' => 'هەڵبژاردە ١',
                            ],
                        ],
                        [
                            'value' => 'option2',
                            'label' => [
                                'en' => 'Option 2',
                                'ar' => 'الخيار 2',
                                'ckb' => 'هەڵبژاردە ٢',
                            ],
                        ],
                    ],
                ],
            ],
            'is_active' => true,
        ];
    }

    /**
     * Create a definition for a specific model type.
     */
    public function forModel(string $modelClass): static
    {
        return $this->state(fn (array $attributes) => [
            'model_type' => $modelClass,
        ]);
    }

    /**
     * Create an inactive definition.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a definition with simple field definitions.
     */
    public function withSimpleFields(): static
    {
        return $this->state(fn (array $attributes) => [
            'field_definitions' => [
                [
                    'key' => 'simple_text',
                    'label' => ['en' => 'Simple Text'],
                    'type' => CustomFieldType::Text->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
            ],
        ]);
    }

    /**
     * Create a definition with complex field definitions.
     */
    public function withComplexFields(): static
    {
        return $this->state(fn (array $attributes) => [
            'field_definitions' => [
                [
                    'key' => 'industry',
                    'label' => [
                        'en' => 'Industry',
                        'ar' => 'الصناعة',
                        'ckb' => 'پیشەسازی',
                    ],
                    'type' => CustomFieldType::Text->value,
                    'required' => false,
                    'show_in_table' => false,
                    'help_text' => [
                        'en' => 'Enter the industry type',
                        'ar' => 'أدخل نوع الصناعة',
                        'ckb' => 'جۆری پیشەسازی بنووسە',
                    ],
                ],
                [
                    'key' => 'priority',
                    'label' => [
                        'en' => 'Priority Level',
                        'ar' => 'مستوى الأولوية',
                        'ckb' => 'ئاستی گرنگی',
                    ],
                    'type' => CustomFieldType::Select->value,
                    'required' => true,
                    'show_in_table' => false,
                    'options' => [
                        [
                            'value' => 'high',
                            'label' => [
                                'en' => 'High Priority',
                                'ar' => 'أولوية عالية',
                                'ckb' => 'گرنگی بەرز',
                            ],
                        ],
                        [
                            'value' => 'medium',
                            'label' => [
                                'en' => 'Medium Priority',
                                'ar' => 'أولوية متوسطة',
                                'ckb' => 'گرنگی مامناوەند',
                            ],
                        ],
                        [
                            'value' => 'low',
                            'label' => [
                                'en' => 'Low Priority',
                                'ar' => 'أولوية منخفضة',
                                'ckb' => 'گرنگی کەم',
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'established_date',
                    'label' => [
                        'en' => 'Established Date',
                        'ar' => 'تاريخ التأسيس',
                        'ckb' => 'بەرواری دامەزراندن',
                    ],
                    'type' => CustomFieldType::Date->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
                [
                    'key' => 'is_preferred',
                    'label' => [
                        'en' => 'Preferred Partner',
                        'ar' => 'شريك مفضل',
                        'ckb' => 'هاوبەشی پەسەند',
                    ],
                    'type' => CustomFieldType::Boolean->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
                [
                    'key' => 'annual_revenue',
                    'label' => [
                        'en' => 'Annual Revenue',
                        'ar' => 'الإيرادات السنوية',
                        'ckb' => 'داهاتی ساڵانە',
                    ],
                    'type' => CustomFieldType::Number->value,
                    'required' => false,
                    'show_in_table' => false,
                    'validation_rules' => ['min:0'],
                ],
                [
                    'key' => 'notes',
                    'label' => [
                        'en' => 'Additional Notes',
                        'ar' => 'ملاحظات إضافية',
                        'ckb' => 'تێبینی زیادە',
                    ],
                    'type' => CustomFieldType::Textarea->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
            ],
        ]);
    }

    /**
     * Create a definition with no field definitions.
     */
    public function withoutFields(): static
    {
        return $this->state(fn (array $attributes) => [
            'field_definitions' => [],
        ]);
    }
}
