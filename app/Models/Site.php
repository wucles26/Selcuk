<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Site extends Model
{
    public function name(): string
    {
        return (string) config('name', 'Selcuk');
    }
}
