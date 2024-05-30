<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Hours;

use Lits\LibCal\Data;

class HoursData extends Data
{
    public ?int $lid = null;
    public ?string $name = null;
    public ?string $category = null;
    public ?string $desc = null;

    public ?string $url = null;
    public ?string $contact = null;
    public ?string $lat = null;
    public ?string $lon = null;
    public ?string $color = null;
    public ?string $fn = null;

    public string $parent_lid;

    /** @var DateData[] $dates */
    public array $dates = [];
}
