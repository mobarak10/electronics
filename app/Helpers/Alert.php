<?php


namespace App\Helpers;


class Alert {

    /**
     * @param $type
     * @param $content
     * @return string
     */
    public static function msg($type, $content) {
        $message = '<div class="alert alert-' . $type . ' alert-dismissible">';
        $message .= '<p class="mb-0"> ' . $content . ' </p>';
        $message .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $message .= '</div>';

        return $message;
    }
}
