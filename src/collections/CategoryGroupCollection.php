<?php
namespace ttempleton\categorygroupsfield\collections;

use ArrayIterator;
use ArrayObject;
use Craft;
use craft\base\Model;
use craft\models\CategoryGroup;

/**
 * Category Group Collection class.
 * 
 * @package ttempleton\categorygroupsfield\collections
 * @author Thomas Templeton
 * @since 1.2.0
 */
class CategoryGroupCollection extends ArrayObject
{
    /**
     * @var CategoryGroup[] The category groups of this collection
     */
    private $_groups = [];

    /**
     * @var string[] The settings applied to this collection, in the order they were applied
     */
    private $_settings = [];

    /**
     * @var bool
     */
    private $_inReverse = false;

    /**
     * CategoryGroupCollection constructor.
     *
     * @param CategoryGroup[] $groups
     */
    public function __construct(array $groups)
    {
        // Ensure it's an array of category groups
        foreach ($groups as $group) {
            if (!($group instanceof CategoryGroup)) {
                throw new \Exception(Craft::t(
                    'category-groups-field',
                    'Trying to create a CategoryGroupCollection that does not contain only category groups'
                ));
            }
        }

        $this->_groups = $groups;
    }

    /**
     * Sets whether the category groups should be returned in reverse order.
     *
     * @since 1.2.1
     * @param bool $reverse
     * @return static
     */
    public function inReverse(bool $reverse = true)
    {
        $this->_inReverse = $reverse;

        if (!in_array('inReverse', $this->_settings)) {
            $this->_settings[] = 'inReverse';
        }

        return $this;
    }

    /**
     * Returns the number of category groups in this collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->_getResults());
    }

    /**
     * Returns the category group collection array.
     *
     * @return CategoryGroup[]
     */
    public function all()
    {
        return $this->_getResults();
    }

    /**
     * Returns the first category group in the collection, or null if the collection is empty.
     *
     * @return CategoryGroup|null
     */
    public function one()
    {
        $groups = $this->_getResults();

        if (!empty($groups)) {
            return $groups[0];
        }

        return null;
    }

    /**
     * Returns the category group at the given index, or null if the index is invalid.
     *
     * @param int $index
     * @return CategoryGroup|null
     */
    public function nth(int $index)
    {
        $groups = $this->_getResults();

        if ($index >= 0 && count($groups) > $index) {
            return $groups[$index];
        }

        return null;
    }

    /**
     * Returns the IDs of the category groups.
     *
     * @return int[]
     */
    public function ids(): array
    {
        $ids = [];

        foreach ($this->_getResults() as $group) {
            $ids[] = $group->id;
        }

        return $ids;
    }

    /**
     * Used when iterating directly over the category group collection in a template.
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->_getResults());
    }

    private function _getResults()
    {
        $groups = $this->_groups;

        foreach ($this->_settings as $setting) {
            switch ($setting) {
                case 'inReverse':
                    if ($this->_inReverse) {
                        $groups = array_reverse($groups, false);
                    }
            }
        }

        return $groups;
    }
}
