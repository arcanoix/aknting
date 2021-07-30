<?php

namespace Modules\Pos\Traits;

use App\Models\Module\Module;

trait CustomFields
{
    public function isCustomFieldsActive(): bool
    {
        if (!module('custom-fields')) {
            return false;
        }

        return Module::alias('custom-fields')->enabled()->first() !== null;
    }
}
