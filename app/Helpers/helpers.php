<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Modules\Setting\Entities\System;

function remove_invalid_charcaters($str)
{
    return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
}

function translate($key)
{
    $local = app()->getLocale();

    try {
        $lang_array = include(base_path('lang/' . $local . '/lang.php'));
        $processed_key = ucfirst(str_replace('_', ' ', remove_invalid_charcaters($key)));

        if (!array_key_exists($key, $lang_array)) {
            $lang_array[$key] = $processed_key;
            $str = "<?php return " . var_export($lang_array, true) . ";";
            file_put_contents(base_path('lang/' . $local . '/lang.php'), $str);
            $result = $processed_key;
        } else {
            $result = __('lang.' . $key);
        }
    } catch (\Exception $exception) {
        $result = __('lang.' . $key);
    }

    return $result;
}
/**
 * Converts date in mysql format to business format
 *
 * @param string $date
 * @param bool $time (default = false)
 * @return strin
 */
 function format_date($date, $show_time = false, $business_details = null)
{
    $format = 'm/d/Y';
    if (!empty($show_time)) {
        $time_format = '';
        if ($time_format == 12) {
            $format .= ' h:i A';
        } else {
            $format .= ' H:i';
        }
    }

    return !empty($date) ? Carbon::createFromTimestamp(strtotime($date))->format($format) : null;
}

function getCroppedImages($cropImages){
    $dataNewImages = [];
    foreach ($cropImages as $img) {
        if (!str_starts_with($img, 'http')) {
            if (strlen($img) < 200) {
                $dataNewImages[] = getBase64Image($img);
            } else {
                $dataNewImages[] = $img;
            }
        }
    }
    return $dataNewImages;
}
 function getBase64Image($Image)
{

    $image_path = str_replace(env("APP_URL") . "/", "", $Image);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $image_path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $image_content = curl_exec($ch);
    curl_close($ch);
    $base64_image = base64_encode($image_content);
    $b64image = "data:image/jpeg;base64," . $base64_image;
    return  $b64image;
}
//Blade directive to format number into required format.
function num_format($expression): string
{
    $currency_precision = 2;
    return number_format($expression,  $currency_precision, '.', ',');
}




//Blade directive to convert.
function format_time($date): ?string
{
    if (!empty($date)) {
        $time_format = 'h:i A';
        if (System::getProperty('time_format') == 24) {
            $time_format = 'H:i';
        }
        return \Carbon\Carbon::createFromTimestamp(strtotime($date))->format($time_format);
    } else {
        return null;
    }
}

function format_datetime($date): ?string
{
    if (!empty($date)) {
        $time_format = 'h:i A';
        if (System::getProperty('time_format') == 24) {
            $time_format = 'H:i';
        }

        return \Carbon\Carbon::createFromTimestamp(strtotime($date))->format('m/d/Y ' . $time_format);
    } else {
        return null;
    }
}

//Blade directive to format currency.
function format_currency($number): string
{
    return '<?php
            $formated_number = "";
            if (session("currency")["currency_symbol_placement"] == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            }
            $formated_number .= number_format((float) ' . $number . ', session("currency")["currency_precision"] , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("currency")["currency_symbol_placement"] == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?>';
}
//Blade directive to return appropiate class according to attendance status
function attendance_status($status): string
{
    return "<?php if($status == 'late'){
                    echo 'badge-warning';
                }elseif($status == 'on_leave'){
                    echo 'badge-danger';
                }elseif ($status == 'present') {
                    echo 'badge-success';
                }?>";
}

//Blade directive to convert.
function replace_space($string): string
{
    return "str_replace(' ', '_', $string)";
}
