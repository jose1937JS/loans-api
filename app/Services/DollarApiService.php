<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DollarApiService
{
	private $host;

	function __construct()
	{
		$this->host = 'https://pydolarvenezuela-api.vercel.app/api/v1/dollar?page=criptodolar&monitor=enparalelovzla';
	}

	public function get()
	{
		return Http::get($this->host);
	}
}
