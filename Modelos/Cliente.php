<?php
	use Illuminate\Database\Eloquent\Model;

	class Cliente extends Model{
	    protected $table = "cliente";
			protected $primaryKey = "email";
			public $incrementing = false;
	    public $timestamps = false;
	}
