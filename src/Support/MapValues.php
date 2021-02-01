<?php

namespace TorMorten\Firestore\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MapValues
{
    public function map($values)
    {
        $mapped = [];
        foreach ($values as $key => $value) {
            $type = array_keys($value)[0];
            if (method_exists($this, $method = 'map' . Str::studly($type))) {
                $mapped[$key] = $this->{$method}(array_values($value)[0]);
            }
        }

        return $mapped;
    }

    protected function mapStringValue($value)
    {
        return (string)$value;
    }

    protected function mapTimestampValue($value)
    {
        return Carbon::parse($value);
    }

    protected function mapArrayValue($value)
    {
        return $this->map($value['values']);
    }

    protected function mapBooleanValue($value)
    {
        return (boolean)$value;
    }

    protected function mapIntegerValue($value)
    {
        return (integer)$value;
    }
}
