<?php

namespace Xoshbin\CustomFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Xoshbin\CustomFields\Enums\CustomFieldType;
use Xoshbin\CustomFields\Models\CustomFieldDefinition;

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
            'name' => $this->faker->words(3, true) . ' Custom Fields',
            'description' => $this->faker->sentence(),
            'field_definitions' => [
                [
                    'key' => 'sample_text',
                    'label' => 'Sample Text Field',
                    'type' => CustomFieldType::Text->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
                [
                    'key' => 'sample_select',
                    'label' => 'Sample Select Field',
                    'type' => CustomFieldType::Select->value,
                    'required' => false,
                    'show_in_table' => false,
                    'options' => [
                        [
                            'value' => 'option1',
                            'label' => 'Option 1',
                        ],
                        [
                            'value' => 'option2',
                            'label' => 'Option 2',
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
                    'label' => 'Simple Text',
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
                    'label' => 'Industry',
                    'type' => CustomFieldType::Text->value,
                    'required' => false,
                    'show_in_table' => false,
                    'help_text' => 'Enter the industry type',
                ],
                [
                    'key' => 'priority',
                    'label' => 'Priority Level',
                    'type' => CustomFieldType::Select->value,
                    'required' => true,
                    'show_in_table' => false,
                    'options' => [
                        [
                            'value' => 'high',
                            'label' => 'High Priority',
                        ],
                        [
                            'value' => 'medium',
                            'label' => 'Medium Priority',
                        ],
                        [
                            'value' => 'low',
                            'label' => 'Low Priority',
                        ],
                    ],
                ],
                [
                    'key' => 'established_date',
                    'label' => 'Established Date',
                    'type' => CustomFieldType::Date->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
                [
                    'key' => 'is_preferred',
                    'label' => 'Preferred Partner',
                    'type' => CustomFieldType::Boolean->value,
                    'required' => false,
                    'show_in_table' => false,
                ],
                [
                    'key' => 'annual_revenue',
                    'label' => 'Annual Revenue',
                    'type' => CustomFieldType::Number->value,
                    'required' => false,
                    'show_in_table' => false,
                    'validation_rules' => ['min:0'],
                ],
                [
                    'key' => 'notes',
                    'label' => 'Additional Notes',
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
