<?php
namespace ttempleton\categorygroupsfield\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Cp;
use craft\helpers\Json as JsonHelper;
use craft\helpers\UrlHelper;
use craft\models\CategoryGroup;
use ttempleton\categorygroupsfield\Plugin;
use ttempleton\categorygroupsfield\collections\CategoryGroupCollection;

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
    public function getSettingsHtml(): ?string
    {
        $allowedGroups = Cp::checkboxSelectFieldHtml([
            'label' => Craft::t('category-groups-field', 'Allowed Groups'),
            'instructions' => Craft::t('category-groups-field', 'Which category groups to allow to be selected for this field.'),
            'id' => 'allowedGroups',
            'name' => 'allowedGroups',
            'options' => $this->_getGroupsSettingsData(Craft::$app->getCategories()->getAllGroups()),
            'values' => $this->allowedGroups,
            'showAllOption' => true,
        ]);

        $singleSelection = Cp::lightswitchFieldHtml([
            'label' => Craft::t('category-groups-field', 'Single Selection'),
            'instructions' => Craft::t('category-groups-field', 'Whether this field is limited to selecting one category group.'),
            'id' => 'singleSelection',
            'name' => 'singleSelection',
            'on' => $this->_isSingleSelection(),
        ]);

        return $allowedGroups . $singleSelection;
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(mixed $value, ?\craft\base\ElementInterface $element = null): string
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
            'values' => $value !== null ? $value->ids() : [],
            'options' => $options,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml(mixed $value, ElementInterface $element): string
    {
        if ($value instanceof CategoryGroupCollection) {
            $html = [];

            foreach ($value->all() as $group) {
                $url = UrlHelper::cpUrl('categories/'. $group->handle);
                $html[] = '<a href="' . $url . '">' . $group->name . '</a>';
            }

            return implode($html, '; ');
        }

        if ($value instanceof CategoryGroup) {
            $url = UrlHelper::cpUrl('categories/'. $value->handle);
            return '<a href="' . $url . '">' . $value->name . '</a>';
        }

        return '';
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        if ($value === null) {
            return null;
        }

        // In case $value is a category group collection already
        if ($value instanceof CategoryGroupCollection) {
            return $value;
        }

        if (!is_array($value)) {
            $value = JsonHelper::decodeIfJson($value);
        }

        $categoriesService = Craft::$app->getCategories();

        // Single selection
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

        // Multi-selection
        if (!empty($value)) {
            // Rather than query for each group individually, get all groups and filter for the ones we want
            $allGroups = $categoriesService->getAllGroups();

            $fieldGroups = array_filter($allGroups, function($group) use($value) {
                return in_array($group->id, $value);
            });
            $fieldGroups = array_values($fieldGroups);

            return new CategoryGroupCollection($fieldGroups);
        }

        // No category groups selected
        return null;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        if ($value instanceof CategoryGroup) {
            // Single selection is enabled, but return an array anyway, in case that setting is disabled in the future
            return [$value->id];
        }

        return $value !== null ? $value->ids() : null;
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
