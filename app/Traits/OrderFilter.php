<?php

namespace App\Traits;

trait OrderFilter
{
 public function order($array)
    {
    	if(count($array) == 2 ||  $array[1] == 'descend' || $array[1] == 'ascend'){
    		$order = $array[1] == 'descend' ? 'DESC' : 'ASC';
    		$this->query->orderBy($array[0], $order);
    	}
    }
}