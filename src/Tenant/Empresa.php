<?php

namespace Sitedigitalweb\Dresses\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model{
 use UsesTenantConnection;
 protected $table = 'dresses_empresas';
 public $timestamps = true;
}


