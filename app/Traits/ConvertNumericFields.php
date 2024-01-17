<?php

namespace App\Traits;

trait ConvertNumericFields
{
    public function convertToEnglish()
    {
        $values = [];
        foreach ($this->numericFields as $item) {
            $values[$item] = $this->$item ? $this->process($this->$item) : null;
        }
        return $values;
    }

    function process($number)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        return str_replace(
            array_merge($persian, $arabic),
            [...range(0, 9), ...range(0, 9)],
            $number
        );
    }
}
