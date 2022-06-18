<?php


namespace App\Helpers;


trait CustomMetaAccessor
{
    /**
     * Get Meta Key Instance
     * @param $meta_key
     * @return mixed
     */
    public function meta($meta_key)
    {
        return $this->metas()->where('meta_key', $meta_key)->first();
    }

    /**
     * Get Meta Value of Current Meta Key
     * @param $meta_key
     * @return |null
     */
    public function getMetaValue($meta_key)
    {
        return (!empty($this->meta($meta_key))) ? $this->meta($meta_key)->meta_value : null;
    }

    /**
     * Get Meta Description of Current Meta key
     * @param $meta_key
     * @return |null
     */
    public function getMetaDescription($meta_key)
    {
        return (!empty($this->meta($meta_key))) ? $this->meta($meta_key)->meta_description : null;
    }


    /**
     * Get Custom Column of Current Meta key
     * @param $meta_key
     * @return |null
     */
    public function getCustomMetaColumn($meta_key, $meta_column = 'meta_value')
    {
        return (!empty($this->meta($meta_key))) ? $this->meta($meta_key)[$meta_column] : null;
    }
}
