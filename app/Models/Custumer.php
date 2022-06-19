<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custumer extends Model
{
    use HasFactory;

    protected $table = 'clientes';
 
    protected $fillable = [
	  'id',
	  'user_id',
	  'Surname',
	  'CreditScore',
	  'Geography',
	  'Gender',
	  'DateOfBirth',
	  'DateOfBirth',
	  'OpenAccountDate',
	  'CurrentAccountNumber',
	  'CreditCardAccountNumber',
	  'Balance',
	  'BalanceDate',
	  'EstimatedSalary'
    ];
}
