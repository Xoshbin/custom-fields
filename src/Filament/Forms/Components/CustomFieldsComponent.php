<?php

namespace Xoshbin\CustomFields\Filament\Forms\Components;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Support\Collection;
use Xoshbin\CustomFields\Enums\CustomFieldType;
use Xoshbin\CustomFields\Models\CustomFieldDefinition;

/**
 * CustomFieldsComponent
 *
 * Generates dynamic form fields based on custom field definitions.
 * Handles both create and edit operations with proper value mutations.
 */
class CustomFieldsComponent
{
    /**
     * Generate custom fields for a specific model type.
     *
     * @param  string  $modelClass  The model class (e.g., 'App\Models\Partner')
     */
    public static function make(string $modelClass): ?Fieldset
    {
        $definition = CustomFieldDefinition::where('model_type', $modelClass)
            ->where('is_active', true)
            ->first();

        if (! $definition || empty($definition->field_definitions)) {
            return null;
        }

        $fields = static::generateFields($definition->getFieldDefinitionsCollection());

        if (empty($fields)) {
            return null;
        }

        return Fieldset::make(__('custom-fields::custom_fields.section_title'))
            ->schema($fields)
            ->columns(2);
    }

    /**
     * Generate form fields from field definitions.
     *
     * @param  Collection<int, array>  $fieldDefinitions
     */
    protected static function generateFields(Collection $fieldDefinitions): array
    {
        $fields = [];

        foreach ($fieldDefinitions as $fieldDefinition) {
            $field = static::generateField($fieldDefinition);

            if ($field) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * Generate a single form field from definition.
     */
    protected static function generateField(array $definition): mixed
    {
        $fieldKey = $definition['key'];
        $fieldType = CustomFieldType::tryFrom($definition['type']);
        $label = static::getLabel($definition['label']);
        $required = $definition['required'] ?? false;
        $validationRules = $definition['validation_rules'] ?? [];

        if (! $fieldType) {
            return null;
        }

        $field = match ($fieldType) {
            CustomFieldType::Text => TextInput::make("custom_fields.{$fieldKey}")
                ->label($label)
                ->maxLength(255),

            CustomFieldType::Textarea => Textarea::make("custom_fields.{$fieldKey}")
                ->label($label)
                ->rows(3)
                ->maxLength(65535),

            CustomFieldType::Number => TextInput::make("custom_fields.{$fieldKey}")
                ->label($label)
                ->numeric(),

            CustomFieldType::Boolean => Checkbox::make("custom_fields.{$fieldKey}")
                ->label($label),

            CustomFieldType::Date => DatePicker::make("custom_fields.{$fieldKey}")
                ->label($label),

            CustomFieldType::Select => Select::make("custom_fields.{$fieldKey}")
                ->label($label)
                ->options(static::getSelectOptions($definition['options'] ?? []))
                ->searchable(),
        };

        // Apply common configurations
        if ($required) {
            $field = $field->required();
        }

        // Apply custom validation rules
        if (! empty($validationRules)) {
            $field = $field->rules($validationRules);
        }

        // Add help text if available
        if (! empty($definition['help_text'])) {
            $helpText = static::getLabel($definition['help_text']);
            $field = $field->helperText($helpText);
        }

        return $field;
    }

    /**
     * Get label from definition.
     */
    protected static function getLabel(string $label): string
    {
        return $label;
    }

    /**
     * Get select options.
     */
    protected static function getSelectOptions(array $options): array
    {
        $result = [];

        foreach ($options as $option) {
            $value = $option['value'] ?? '';
            $label = $option['label'] ?? $value;
            $result[$value] = $label;
        }

        return $result;
    }

    /**
     * Mutate form data before filling (for edit operations).
     */
    public static function mutateFormDataBeforeFill(array $data, string $modelClass): array
    {
        if (! isset($data['id'])) {
            return $data;
        }

        $model = $modelClass::find($data['id']);

        if (! $model || ! method_exists($model, 'getCustomFieldValues')) {
            return $data;
        }

        $customFieldValues = $model->getCustomFieldValues();

        if (! empty($customFieldValues)) {
            $data['custom_fields'] = $customFieldValues;
        }

        return $data;
    }

    /**
     * Mutate form data before save (for create and edit operations).
     */
    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Extract custom fields from the main data array
        $customFields = $data['custom_fields'] ?? [];
        unset($data['custom_fields']);

        // Store custom fields separately for later processing
        $data['_custom_fields'] = $customFields;

        return $data;
    }

    /**
     * Handle custom fields after model save.
     */
    public static function handleAfterSave($record, array $data): void
    {
        if (! method_exists($record, 'setCustomFieldValues')) {
            return;
        }

        $customFields = $data['_custom_fields'] ?? [];

        if (! empty($customFields)) {
            $record->setCustomFieldValues($customFields);
        }
    }

    /**
     * Get validation rules for custom fields.
     */
    public static function getValidationRules(string $modelClass): array
    {
        $definition = CustomFieldDefinition::where('model_type', $modelClass)
            ->where('is_active', true)
            ->first();

        if (! $definition) {
            return [];
        }

        $rules = [];

        foreach ($definition->getFieldDefinitionsCollection() as $fieldDefinition) {
            $fieldKey = $fieldDefinition['key'];
            $fieldType = CustomFieldType::tryFrom($fieldDefinition['type']);
            $required = $fieldDefinition['required'] ?? false;
            $customRules = $fieldDefinition['validation_rules'] ?? [];

            $fieldRules = [];

            if ($required) {
                $fieldRules[] = 'required';
            }

            if ($fieldType) {
                $fieldRules = array_merge($fieldRules, $fieldType->getValidationRules());
            }

            if (! empty($customRules)) {
                $fieldRules = array_merge($fieldRules, $customRules);
            }

            if (! empty($fieldRules)) {
                $rules["custom_fields.{$fieldKey}"] = $fieldRules;
            }
        }

        return $rules;
    }

    /**
     * Check if a model has custom fields defined.
     */
    public static function hasCustomFields(string $modelClass): bool
    {
        return CustomFieldDefinition::where('model_type', $modelClass)
            ->where('is_active', true)
            ->exists();
    }
}
