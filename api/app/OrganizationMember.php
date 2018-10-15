<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends APIModel
{
    protected $table = 'Organization_members';
    protected $fillable = ['type', 'account_id', 'organization_id', 'status'];
}
