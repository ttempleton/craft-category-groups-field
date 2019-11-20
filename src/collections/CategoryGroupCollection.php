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
     * @var CategoryGroup[]
     */
    private $groups = [];

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

        $this->groups = $groups;
    }

    /**
     * Returns the number of category groups in this collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->groups);
    }

    /**
     * Returns the category group collection array.
     *
     * @return CategoryGroup[]
     */
    public function all()
    {
        return $this->groups;
    }

    /**
     * Returns the first category group in the collection, or null if the collection is empty.
     *
     * @return CategoryGroup|null
     */
    public function one()
    {
        if (!empty($this->groups)) {
            return $this->groups[0];
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
        if ($index >= 0 && count($this->groups) > $index) {
            return $this->groups[$index];
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

        foreach ($this->groups as $group) {
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
        return new ArrayIterator($this->groups);
    }
}
