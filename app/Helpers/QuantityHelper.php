<?php


namespace App\Helpers;


trait QuantityHelper
{
    /**
     * Get single formatted quantity for convert
     * @param $quantity
     * @param $unit_length
     * @return mixed
     */
    protected function formattedSingleQuantity($quantity, $unit_length)
    {
        $input = '';
        for ($i = 0; $i < $unit_length; $i++) {
            if (!array_key_exists($i, $quantity) || $quantity[$i] == null) {
                $quantity[$i] = 0;
            }
            $input .= $quantity[$i];

            if ($unit_length - $i > 1){
                $input .= '/';
            }
        }

        return $input;
    }
}
