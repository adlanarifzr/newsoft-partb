<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'list_name',
        'address',
        'latitude',
        'longitude',
        // 'submitter_id',
    ];

    public function submitter()
    {
        return $this->belongsTo('App\User', 'submitter_id');
    }

    /**
     * Computes the distance between two coordinates.
     *
     * Implementation based on reverse engineering of
     * <code>google.maps.geometry.spherical.computeDistanceBetween()</code>.
     *
     * @param float $lat1 Latitude from the first point.
     * @param float $lng1 Longitude from the first point.
     * @param float $lat2 Latitude from the second point.
     * @param float $lng2 Longitude from the second point.
     * @param float $radius (optional) Radius in meters.
     *
     * @return float Distance in kilometers with 3 precisions
     */
    public function distanceFrom($lat1, $lng1, $radius = 6378137)
    {
        $lat2 = $this->latitude;
        $lng2 = $this->longitude;

        static $x = M_PI / 180;
        $lat1 *= $x; $lng1 *= $x;
        $lat2 *= $x; $lng2 *= $x;
        $distance = 2 * asin(sqrt(pow(sin(($lat1 - $lat2) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin(($lng1 - $lng2) / 2), 2)));

        return round($distance * $radius / 1000, 3);
    }
}
