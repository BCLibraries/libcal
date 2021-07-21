<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;
use Lits\LibCal\Exception\DataException;

final class CategorySpaceData extends Data
{
    public int $cid;
    public ?string $name = null;
    public int $formid;
    public bool $public;
    public ?bool $admin_only = null;

    /** @var ItemSpaceData[] $items */
    public ?array $items = null;

    /**
     * Decode JSON.
     *
     * @param string $data JSON to decode.
     * @return mixed The decoded JSON with the object or objects fixed by
     *  making the items property always hold objects.
     * @throws DataException
     */
    protected static function decodeJson(string $data)
    {
        /** @var object|mixed[]|null $decoded */
        $decoded = parent::decodeJson($data);

        if (\is_array($decoded)) {
            foreach (\array_keys($decoded) as $key) {
                if (\is_object($decoded[$key])) {
                    $decoded[$key] = self::fixObject($decoded[$key]);
                }
            }

            return $decoded;
        }

        if (\is_object($decoded)) {
            $decoded = self::fixObject($decoded);
        }

        return $decoded;
    }

    /**
     * Fix the items property of the specified object.
     *
     * @param object $object The object to fix.
     * @return object The object with the items property fixed as objects.
     */
    private static function fixObject(object $object): object
    {
        if (isset($object->items) && \is_array($object->items)) {
            $object->items = self::fixObjectItems($object->items);
        }

        return $object;
    }

    /**
     * Fix the items array from an object to always contain objects.
     *
     * @param mixed[] $items An array of an items property from an object.
     * @return mixed[] The items fixed if necessary if the value is an
     *  integer by creating an object with a property id of that integer.
     */
    private static function fixObjectItems(array $items): array
    {
        foreach (\array_keys($items) as $key) {
            if (\is_int($items[$key])) {
                $items[$key] = (object) ['id' => $items[$key]];
            }
        }

        return $items;
    }
}
