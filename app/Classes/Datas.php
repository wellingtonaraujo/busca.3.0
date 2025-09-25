<?php

namespace App\Classes;


class Datas {
	protected $value;

	public static function parse_br($data){

		$temp = explode(' ', $data);

		$data = $temp[0];

		if (strstr($data, '-')) {
			$value = explode('-', $data);
			return $value[2] . '/' . $value[1] . '/' . $value[0];
		}else{
			return $data;
		}
	}

	public static function parse_us($data){
		if (strstr($data, '/')) {
			$value = explode('/', $data);
			return $value[2] . '-' . $value[1] . '-' . $value[0];
		}else{
			return $data;
		}
	}

	public static function remCaracter($caracter, $date){
		$temp = explode($caracter, $date);
		$newValue = '';
		for ($i=0; $i <= count($temp); $i++) {
			$n = count($temp) - 1;
			if($i < 2){
				$newValue .= $temp[$i] . '-';
			}elseif ($i == 2){
				$newValue .= $temp[$i];
			}
		}
		return $newValue;
	}

	public static function hasExpired($value){
		// $value = Datas::parse_us($value);
		$data = date("Y-m-d",strtotime($value));
        $hoje = strtotime(date("Y-m-d"));
        if (strtotime(Datas::parse_us($value)) > $hoje) {
        	return true;
        }else{
        	return false;
        }
	}

	public static function horaExpirada($hora_limite){
	    $hora_limite = strtotime($hora_limite);
        $hora_atual= strtotime(date("H:i:s"));

        if($hora_limite <= $hora_atual) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Recebe um dateTipe e retorna um date no padrao americano Y-m-d
     * @param $data
     */
    public static function getDateUs($data){
	    $temp = strtotime($data);
	    $newDate = date('Y-m-d', $temp);
	    $arrayData = explode('-', $newDate);
	    $is_date = count($arrayData) == 3 ? checkdate($arrayData[1], $arrayData[2], $arrayData[0]) : null;

	    return $is_date == true ? $newDate : null;
    }

    public static function validarData($data){
        $array = explode('-', $data);
        $array = count($array) > 0 ? $array : explode('/', $data);
        $vabs = abs($array[0]);
        return $vabs == 0 ? false : true;
    }

    /**
     * @param $dataAtual
     * @param numDias $
     * @param razao|null $
     * @return date
     */
    public static function nextDate($dataAtual, $numDias){
        $dias = '+' . $numDias .  'days';
        return date('Y-m-d', strtotime($dias, strtotime($dataAtual)));
    }

    public static function previewDate($dataAtual, $numDias){
        $dias = '-' . $numDias .  'days';
        return date('Y-m-d', strtotime($dias, strtotime($dataAtual)));
    }
}
