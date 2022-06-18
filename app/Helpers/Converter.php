<?php

namespace App\Helpers;

use App\Models\Unit;
use Illuminate\Support\Facades\Log;

class Converter {

    /**
     * @param $data
     * @return array
     */
    public static function separator ($data) {
        return explode('/', $data);
    }

    /**
     * @param $input
     * @param $standard
     * @param string $format
     * @return array
     */
    public static function convert($input, $standard, $format = 'u') {
        // output
        $output = [
            'input' => $input,
        ];

        // get unit details
        $unit = Unit::where('code', $standard)->first();
        $relation = self::separator($unit->relation);
        $labels = self::separator($unit->labels);
        $last = count($labels) - 1;

        $output['unit'] = $unit->name;
        $output['labels'] = $labels;
        $output['labelsInLine'] = $unit->labels;
        $output['relation'] = $unit->relation;

        // for single unit case
        if($format == 'u') {
            // get quantity details
            $quantities = self::separator($input);
            $record = [];

            foreach ($quantities as $key => $amount) {
                $total = intval($amount);

                for($index = $key + 1; $index <= $last; $index++) {
                    $total *= $relation[$index];
                }

                $record[] = $total;
            }

            $output['result'] = array_sum($record);
            $output['resultWithUnit'] = array_sum($record) . ' ' . $labels[$last];
            $output['display'] = array_sum($record) . ' ' . $labels[$last];
        }

        // for display case
        if($format == 'd') {
            $record = [];

            for($index = $last; $index >= 0; $index--) {
                $divisor  = floatval($relation[$index]);

                // $remainder = $input % $divisor; // dividend is equal to input
                $remainder = fmod($input, $divisor); // dividend is equal to input and esc modulo by zero error

                $input = ($input - $remainder) / $divisor; // quotient is equal to input

                if($index == 0) {
                    $record[] = $input;
                    $recordWithUnit[] = $input . ' ' . $labels[$index];

                    if($input != 0) $recordWithCleanUnit[] = $input . ' ' . $labels[$index];
                } else {
                    $record[] = $remainder;
                    $recordWithUnit[] = $remainder . ' ' . $labels[$index];

                    $remainder = self::synthesize($remainder);
                    if ($remainder != 0 ) {
                        $recordWithCleanUnit[] = $remainder . ' ' . $labels[$index];
                    }
                }
            }

            $output['result'] = array_reverse($record);
            $output['resultWithUnit'] = array_reverse($recordWithUnit);
            $output['displayAllUnits'] = join(' ', array_reverse($recordWithUnit));
            $output['display'] = join(' ', array_reverse($recordWithCleanUnit ?? []));
        }

        return $output;
    }

    private static function synthesize($value) {
        return number_format($value, 2);
    }

}
