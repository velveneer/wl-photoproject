<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Employer;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model {

    use HasFactory;
    
    protected $table = 'jobs_listings';

    protected $guarded = [];

    public function employer() {
        return $this->belongsTo(Employer::class);
    }

    public function tags() {

        return $this->belongsToMany(Tag::class, foreignPivotKey: "job_listing_id");
    }
}