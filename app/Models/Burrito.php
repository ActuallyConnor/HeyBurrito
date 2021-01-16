<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Burrito extends Model {
	use HasFactory;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'burrito_giver',
		'burrito_receiver',
		'message_sent_to_giver',
		'message_sent_to_receiver'
	];
}
