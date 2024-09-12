<?php

/**
 * RuleTestRequest.php
 * Copyright (c) 2019 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Api\V1\Requests\Models\Rule;

use Carbon\Carbon;
use FireflyIII\Support\Request\ChecksLogin;
use FireflyIII\Support\Request\ConvertsDataTypes;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TestRequest
 */
class TestRequest extends FormRequest
{
    use ChecksLogin;
    use ConvertsDataTypes;

    public function getTestParameters(): array
    {
        return [
            'page'     => $this->getPage(),
            'start'    => $this->getDate('start'),
            'end'      => $this->getDate('end'),
            'accounts' => $this->getAccounts(),
        ];
    }

    private function getPage(): int
    {
        return 0 === (int)$this->query('page') ? 1 : (int)$this->query('page');
    }

    private function getDate(string $field): ?Carbon
    {
        $value = $this->query($field);
        if (is_array($value)) {
            return null;
        }
        $value = (string)$value;

        return null === $this->query($field) ? null : Carbon::createFromFormat('Y-m-d', substr($value, 0, 10));
    }

    private function getAccounts(): array
    {
        return $this->get('accounts');
    }

    public function rules(): array
    {
        return [
            'start'      => 'date',
            'end'        => 'date|after_or_equal:start',
            'accounts'   => '',
            'accounts.*' => 'required|exists:accounts,id|belongsToUser:accounts',
        ];
    }
}
