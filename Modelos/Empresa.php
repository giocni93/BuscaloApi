<?php
	use Illuminate\Database\Eloquent\Model;

	class Empresa extends Model{
	    protected $table = "empresa";
			protected $primaryKey = "email";
			public $incrementing = false;
	    public $timestamps = false;
	}
