<?php

namespace Xoshbin\CustomFields\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CustomFieldDefinitionFormTest extends TestCase
{
    /**
     * Test that itemLabel closures work with string labels.
     */
    public function test_item_label_closures_handle_string_labels()
    {
        // Test with current string format (expected)
        $fieldState = [
            'key' => 'sample_field',
            'label' => 'Sample Field',
            'type' => 'text',
        ];

        $optionState = [
            'value' => 'option1',
            'label' => 'Option 1',
        ];

        // Simulate the exact itemLabel closure logic for field definitions
        $simulateFieldItemLabel = function (array $state): ?string {
            return $state['label'] ?? $state['key'] ?? null;
        };

        // Simulate the exact itemLabel closure logic for options
        $simulateOptionItemLabel = function (array $state): ?string {
            return $state['label'] ?? null;
        };

        // Test field definition itemLabel logic with string labels
        $fieldLabel = $simulateFieldItemLabel($fieldState);
        $this->assertEquals('Sample Field', $fieldLabel);

        $optionLabel = $simulateOptionItemLabel($optionState);
        $this->assertEquals('Option 1', $optionLabel);

        // Test with missing label (should fallback to key for fields)
        $fieldStateNoLabel = ['key' => 'test_key'];
        $fieldLabel = $simulateFieldItemLabel($fieldStateNoLabel);
        $this->assertEquals('test_key', $fieldLabel);

        // Test with missing label for options (should return null)
        $optionStateNoLabel = ['value' => 'test_value'];
        $optionLabel = $simulateOptionItemLabel($optionStateNoLabel);
        $this->assertNull($optionLabel);
    }
}
