<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Action;
use Lits\LibCal\Client;
use Lits\LibCal\Data\Hours\HoursData;
use Lits\LibCal\Exception\ActionException;

/** Action to get opening hours for a location. */
class HoursAction extends Action
{
    use TraitIdMultiple;
    use TraitCache;
    use TraitCache;

    public ?string $from = null;
    public ?string $to = null;

    /**
     * Set the date to retrieve hours from.
     *
     * @param string|\DateTimeInterface|null $from Value to set. Strings should
     *                                             be in yyyy-mm-dd format
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid date is specified.
     */
    final public function setFrom($from): self
    {
        $this->from = self::buildDateString($from);

        return $this;
    }

    /**
     * Set the date to retrieve hours to.
     *
     * @param string|\DateTimeInterface|null $to Value to set. Strings should
     *                                           be in yyyy-mm-dd format
     * @return self A reference to this object for method chaining.
     * @throws ActionException If an invalid date is specified.
     */
    final public function setTo($to): self
    {
        $this->to = self::buildDateString($to);

        return $this;
    }

    /**
     * @return HoursData[]
     * @throws \Lits\LibCal\Exception\ClientException
     */
    final public function send(): array
    {
        $uri = '/' . Client::VERSION . '/hours';
        $uri = $this->addId($uri);
        $uri = self::addQuery($uri, 'from', $this->from);
        $uri = self::addQuery($uri, 'to', $this->to);

        /** @var HoursData[] $result */
        $result = $this->memoize(
            $uri,
            fn (string $uri) => HoursData::fromJsonAsArray(
                $this->client->get($uri)
            )
        );

        return $result;
    }

    /**
     * Return a date string in 'yyyy-mm-dd' format.
     *
     * @param string|\DateTimeInterface|null $date The date.
     * @return string|null The date in 'yyyy-mm-dd' format. Null if null input.
     * @throws ActionException If an invalid date is specified.
     */
    private static function buildDateString($date): ?string
    {
        if (\is_null($date)) {
            return null;
        }

        if ($date instanceof \DateTimeInterface) {
            return $date->format('Y-m-d');
        }

        if (!self::dateStringIsValid($date)) {
            throw new ActionException('Invalid date specified');
        }

        return $date;
    }

    /**
     * Is string date in 'yyyy-mm-dd' format?
     *
     * @param string $date Value to set.
     * @return bool true if the string is in 'yyyy-mm-dd' format,
     *              false if not
     */
    private static function dateStringIsValid(string $date): bool
    {
        $date = \filter_var(
            $date,
            \FILTER_VALIDATE_REGEXP,
            ['options' => ['regexp' => '/^\d{4}-\d{2}-\d{2}$/']]
        );

        return $date !== false;
    }
}
