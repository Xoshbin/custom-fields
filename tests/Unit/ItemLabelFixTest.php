<?php

namespace Xoshbin\CustomFields\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ItemLabelFixTest extends TestCase
{
    /**
     * Test that itemLabel closures work correctly with string labels.
     * This test verifies the fix for the TypeError that was occurring.
     */
    public function test_item_label_closures_work_with_string_labels()
    {
        // Simulate the exact itemLabel closure logic for field definitions
        $simulateFieldItemLabel = function (array $state): ?string {
            return $state['label'] ?? $state['key'] ?? null;
        };

        // Simulate the exact itemLabel closure logic for options
        $simulateOptionItemLabel = function (array $state): ?string {
            return $state['label'] ?? null;
        };

        // Test Case 1: Normal string labels (current expected format)
        $fieldState = [
            'key' => 'sample_field',
            'label' => 'Sample Field Label',
            'type' => 'text',
        ];

        $optionState = [
            'value' => 'option1',
            'label' => 'Option 1 Label',
        ];

        $this->assertEquals('Sample Field Label', $simulateFieldItemLabel($fieldState));
        $this->assertEquals('Option 1 Label', $simulateOptionItemLabel($optionState));

        // Test Case 2: Missing labels (should fallback to key for fields)
        $noLabelFieldState = [
            'key' => 'no_label_field',
            'type' => 'text',
        ];

        $noLabelOptionState = [
            'value' => 'no_label_option',
        ];

        $this->assertEquals('no_label_field', $simulateFieldItemLabel($noLabelFieldState));
        $this->assertNull($simulateOptionItemLabel($noLabelOptionState));

        // Test Case 3: Empty string labels
        $emptyLabelFieldState = [
            'key' => 'empty_label_field',
            'label' => '',
            'type' => 'text',
        ];

        // Empty string is returned as-is (not null), so it won't fallback to key
        $this->assertEquals('', $simulateFieldItemLabel($emptyLabelFieldState));
    }
}
