<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Hours;

use Lits\LibCal\Data;

class DateData extends Data
{
    public ?string $status = null;

    public ?string $note = null;

    /** @var OpeningHoursData[] */
    public array $hours = [];
}
