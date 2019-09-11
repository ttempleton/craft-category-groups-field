<?php
namespace ttempleton\categorygroupsfield\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Json as JsonHelper;
use craft\models\CategoryGroup;

/**
 * Category Groups field type class.
 * 
 * @package ttempleton\categorygroupsfield\fields
 * @author Thomas Templeton
 * @since 1.0.0
 */
class CategoryGroupsField extends Field
{
    /**
     * @var bool Whether this field is limited to selecting one category group
     */
    public $singleSelection = false;

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
        return Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'lightswitchField', [[
            'label' => Craft::t('category-groups-field', 'Single Selection Mode'),
            'instructions' => Craft::t('category-groups-field', 'Whether this field is limited to selecting one category group.'),
            'id' => 'singleSelection',
            'name' => 'singleSelection',
            'on' => $this->singleSelection,
        ]]);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $options = [];
        $allGroups = Craft::$app->getCategories()->getAllGroups();

        foreach ($allGroups as $group) {
            $options[] = [
                'label' => $group->name,
                'value' => $group->id,
            ];
        }

        if ($this->singleSelection) {
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
    public function normalizeValue($value, ElementInterface $element = null)
    {
        $categoriesService = Craft::$app->getCategories();
        $value = JsonHelper::decodeIfJson($value);

        if ($this->singleSelection) {
            // Just query for that one
            if (is_array($value)) {
                $value = $value[0];
            }

            return $value !== null ? $categoriesService->getGroupById($value) : null;
        }

        $fieldGroups = null;

        if (!empty($value)) {
            // Rather than query for each group individually, get all groups and filter for the ones we want
            $allGroups = $categoriesService->getAllGroups();

            $fieldGroups = array_filter($allGroups, function($group) use($value) {
                return in_array($group->id, $value);
            });
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
}
