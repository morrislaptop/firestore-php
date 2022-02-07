<?php

namespace TorMorten\Firestore\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ReverseMapValues
{
    public function map($values)
    {
        foreach ($values as $key => $value) {
            foreach (get_class_methods($this) as $method) {
                if ($method !== 'map') {
                    if ($formatted = $this->{$method}($value)) {
                        $values[$key] = $formatted;
                    }
                }
            }
        }

        return $values;
    }

    protected function mapNumericValue($value)
    {
        if (is_numeric($value)) {
            return ['doubleValue' => $value];
        }

        return false;
    }

    protected function mapStringValue($value)
    {
        if (is_string($value)) {
            return ['stringValue' => (string)$value];
        }

        return false;
    }

    protected function mapNullValue($value)
    {
        if (is_null($value)) {
            return ['nullValue' => null];
        }

        return false;
    }

    protected function mapArrayValue($value)
    {
        if (is_array($value)) {
            return ['arrayValue' => null];
        }

        return false;
    }
}
