<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganizationLogo extends APIModel
{
    protected $table = 'organization_logos';
    protected $fillable = ['organization_id', 'url'];
}
