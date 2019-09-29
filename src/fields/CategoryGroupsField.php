<?php
namespace ttempleton\categorygroupsfield\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Json as JsonHelper;
use craft\models\CategoryGroup;
use ttempleton\categorygroupsfield\Plugin;

/**
 * Category Groups field type class.
 * 
 * @package ttempleton\categorygroupsfield\fields
 * @author Thomas Templeton
 * @since 1.0.0
 */
class CategoryGroupsField extends Field implements PreviewableFieldInterface
{
    /**
     * @var string|string[]
     */
    public $allowedGroups = '*';

    /**
     * @var bool Whether this field is limited to selecting one category group
     */
    public $singleSelection;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('category-groups-field', 'Category Groups');
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        $allowedGroups = Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'checkboxSelectField', [[
            'label' => Craft::t('category-groups-field', 'Allowed Groups'),
            'instructions' => Craft::t('category-groups-field', 'Which category groups to allow to be selected for this field.'),
            'id' => 'allowedGroups',
            'name' => 'allowedGroups',
            'options' => $this->_getGroupsSettingsData(Craft::$app->getCategories()->getAllGroups()),
            'values' => $this->allowedGroups,
            'showAllOption' => true,
        ]]);

        $singleSelection = Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'lightswitchField', [[
            'label' => Craft::t('category-groups-field', 'Single Selection'),
            'instructions' => Craft::t('category-groups-field', 'Whether this field is limited to selecting one category group.'),
            'id' => 'singleSelection',
            'name' => 'singleSelection',
            'on' => $this->_isSingleSelection(),
        ]]);

        return $allowedGroups . $singleSelection;
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $options = $this->_getGroupsInputData();

        if ($this->_isSingleSelection()) {
            return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
                'name' => $this->handle,
                'value' => $value !== null ? $value->id : null,
                'options' => array_merge([[
                    'label' => '',
                    'value' => null,
                ]], $options),
            ]);
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/multiselect', [
            'name' => $this->handle,
            'values' => $value !== null ? $this->_getGroupIds($value) : [],
            'options' => $options,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml($value, ElementInterface $element): string
    {
        // If multi-selection, return all selected groups' names
        if (is_array($value)) {
            return implode($this->_getGroupNames($value), '; ');
        }

        // If single selection, return just that group's name
        if ($value instanceof CategoryGroup) {
            return $value->name;
        }

        return '';
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value === null) {
            return null;
        }

        $categoriesService = Craft::$app->getCategories();

        if (!is_array($value)) {
            $value = JsonHelper::decodeIfJson($value);
        }

        if ($this->_isSingleSelection()) {
            // Just query for that one
            if (is_array($value)) {
                $value = $value[0];
            }

            if ($value instanceof CategoryGroup) {
                return $value;
            }

            return $value !== null ? $categoriesService->getGroupById($value) : null;
        }

        $fieldGroups = null;

        if (!empty($value)) {
            // In case $value is an array of category groups already
            if ($value[0] instanceof CategoryGroup) {
                return $value;
            }

            // Rather than query for each group individually, get all groups and filter for the ones we want
            $allGroups = $categoriesService->getAllGroups();

            $fieldGroups = array_filter($allGroups, function($group) use($value) {
                return in_array($group->id, $value);
            });
            $fieldGroups = array_values($fieldGroups);
        }

        return $fieldGroups;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        if ($value instanceof CategoryGroup) {
            // Single selection is enabled, but return an array anyway, in case that setting is disabled in the future
            return [$value->id];
        }

        return $value !== null ? $this->_getGroupIds($value) : null;
    }

    /**
     * Returns the IDs of category groups.
     *
     * @param array $groups
     * @return int[] array of the groups' IDs
     */
    private function _getGroupIds(array $groups): array
    {
        $ids = [];

        foreach ($groups as $group) {
            $ids[] = $group->id;
        }

        return $ids;
    }

    /**
     * Returns the names of category groups.
     *
     * @param array $groups
     * @return string[] array of the groups' names
     */
    private function _getGroupNames(array $groups): array
    {
        $names = [];

        foreach ($groups as $group) {
            $names[] = $group->name;
        }

        return $names;
    }

    private function _getGroupsSettingsData(array $groups): array
    {
        $settings = [];

        foreach ($groups as $group) {
            $settings[] = [
                'label' => $group->name,
                'value' => 'group:' . $group->uid,
            ];
        }

        return $settings;
    }

    private function _getGroupsInputData(): array
    {
        $options = [];

        foreach (Craft::$app->getCategories()->getAllGroups() as $group) {
            $groupSource = 'group:' . $group->uid;

            if (!is_array($this->allowedGroups) || in_array($groupSource, $this->allowedGroups)) {
                $options[] = [
                    'label' => $group->name,
                    'value' => $group->id,
                ];
            }
        }

        return $options;
    }

    private function _isSingleSelection(): bool
    {
        if ($this->singleSelection !== null) {
            return $this->singleSelection;
        }

        return Plugin::getInstance()->getSettings()->singleSelectionDefault;
    }
}
