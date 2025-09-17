<?php

use Xoshbin\CustomFields\Models\CustomFieldDefinition;
use Xoshbin\CustomFields\Models\CustomFieldValue;
use Xoshbin\CustomFields\Tests\Models\Partner;

describe('HasCustomFields Trait', function () {
    it('provides custom field values relationship', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        $definition = CustomFieldDefinition::factory()->forModel(Partner::class)->create();

        CustomFieldValue::factory()
            ->forDefinition($definition)
            ->forModel(Partner::class, $partner->id)
            ->textValue('Technology')
            ->create(['field_key' => 'industry']);

        expect($partner->customFieldValues())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphMany::class)
            ->and($partner->customFieldValues)->toHaveCount(1);
    });

    it('can get custom field definition for model type', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        $definition = CustomFieldDefinition::factory()
            ->forModel(Partner::class)
            ->create(['is_active' => true]);

        $retrievedDefinition = $partner->getCustomFieldDefinition();
        expect($retrievedDefinition->id)->toBe($definition->id);
    });

    it('returns null when no active definition exists', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        // Create inactive definition
        CustomFieldDefinition::factory()
            ->forModel(Partner::class)
            ->inactive()
            ->create();

        expect($partner->getCustomFieldDefinition())->toBeNull();
    });

    it('can set and get custom field values', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        $definition = CustomFieldDefinition::factory()
            ->forModel(Partner::class)
            ->withComplexFields()
            ->create();

        $values = [
            'industry' => 'Technology',
            'priority' => 'high',
            'is_preferred' => true,
            'annual_revenue' => 1000000,
        ];

        $partner->setCustomFieldValues($values);

        $retrievedValues = $partner->getCustomFieldValues();
        expect($retrievedValues['industry'])->toBe('Technology')
            ->and($retrievedValues['priority'])->toBe('high')
            ->and($retrievedValues['is_preferred'])->toBeTrue()
            ->and($retrievedValues['annual_revenue'])->toBe(1000000.0);
    });

    it('can set and get individual custom field values', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        $definition = CustomFieldDefinition::factory()
            ->forModel(Partner::class)
            ->withSimpleFields()
            ->create();

        $partner->setCustomFieldValue('simple_text', 'Test Value');

        expect($partner->getCustomFieldValue('simple_text'))->toBe('Test Value');
    });

    it('validates required fields when setting values', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        $definition = CustomFieldDefinition::factory()
            ->forModel(Partner::class)
            ->create([
                'field_definitions' => [
                    [
                        'key' => 'required_field',
                        'label' => 'Required Field',
                        'type' => 'text',
                        'required' => true,
                        'show_in_table' => false,
                    ],
                ],
            ]);

        // The setCustomFieldValues method validates through Laravel validator
        // Let's test that it properly validates required fields
        $errors = $partner->validateCustomFieldValues([]);
        expect($errors)->not->toBeEmpty()
            ->and($errors)->toHaveKey('required_field');
    });

    it('deletes custom field values when model is deleted', function () {
        $partner = new Partner(['name' => 'Test Partner']);
        $partner->save();

        $definition = CustomFieldDefinition::factory()
            ->forModel(Partner::class)
            ->withSimpleFields()
            ->create();

        $partner->setCustomFieldValue('simple_text', 'Test Value');

        expect(CustomFieldValue::count())->toBe(1);

        $partner->delete();

        expect(CustomFieldValue::count())->toBe(0);
    });
});
