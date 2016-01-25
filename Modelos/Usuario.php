<?php
	use Illuminate\Database\Eloquent\Model;

	class Usuario extends Model{
	    protected $table = "usuario";
			protected $primaryKey = "id";
			public $incrementing = false;
	    public $timestamps = false;
	}
