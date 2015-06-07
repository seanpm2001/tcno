<?php 

/**
* Tcno
*
* TC Kimlik numaraları 11 basamaktan oluşmaktadır. 
* İlk 9 basamak arasında kurulan bir algoritma bize 10. 
* basmağı, ilk 10 basamak arasında kurulan algoritma ise bize 11. basamağı verir.
*/
class Tcno
{

	public function create()
	{
		$newTcno = mt_rand(100000000, 999999999);

		$newTcno = $newTcno . $this->calculate_letter_ten($newTcno);

		$newTcno = $newTcno . $this->calculate_letter_eleven($newTcno);

		return intval($newTcno);
	}

	public function validate($tcno)
	{
		$splitted_tcno = str_split($tcno);
		
		// *11 hanelidir.
		if (strlen($tcno) != 11) 
		{
			return false;
			// throw new Exception("TC No 11 hane olmalıdır.");	
		}

		// *Her hanesi rakamsal değer içerir.
		if ( ! is_numeric($tcno)) 
		{
			return false;
			// throw new Exception("TC No yalnızca sayısal değerlerden oluşmalıdır.");	
		}

		// *İlk hane 0 olamaz. 
		if (strtr($tcno, 0, 1) == 0) 
		{
			return false;
			// throw new Exception("TC No sıfır (0) rakamı ile başlayamaz.");	
		}

		// *1. 3. 5. 7. ve 9. hanelerin toplamının 7 katından, 2. 4. 6. ve 8. hanelerin toplamı çıkartıldığında, 
		// elde edilen sonucun 10'a bölümünden kalan, yani Mod10'u bize 10. haneyi verir.
		$ten = $this->calculate_letter_ten($tcno);

		if ( $ten != intval($splitted_tcno[9]) ) 
		{
			return false;
			// throw new Exception("TC No 10. hane hatalı.");
		}

		// *1. 2. 3. 4. 5. 6. 7. 8. 9. ve 10. hanelerin toplamından elde edilen sonucun 10'a bölümünden kalan, 
		// yani Mod10'u bize 11. haneyi verir.
		$eleven = $this->calculate_letter_eleven($tcno);

		if ( $eleven != intval($splitted_tcno[10]) ) 
		{
			return false;
			// throw new Exception("TC No 11. hane hatalı.");
		}

		return true;
	}

	function calculate_letter_ten($tcno)
	{
		$splitted_tcno = str_split($tcno);

		$odd = array_filter($splitted_tcno, function($value, $key)
		{
			if (in_array($key, [0, 2, 4, 6, 8])) 
			{
				return [$key => $value];
			}
		}, ARRAY_FILTER_USE_BOTH);

		$odd = array_sum($odd) * 7;

		$even = array_filter($splitted_tcno, function($value, $key)
		{
			if (in_array($key, [1, 3, 5, 7])) 
			{
				return [$key => $value];
			}
		}, ARRAY_FILTER_USE_BOTH);

		$even = array_sum($even);

		return ($odd - $even) % 10;
	}

	function calculate_letter_eleven($tcno)
	{
		$splitted_tcno = str_split($tcno);

		$sum = array_filter($splitted_tcno, function($value, $key)
		{
			if ( ! in_array($key, [10])) 
			{
				return [$key => $value];
			}
		}, ARRAY_FILTER_USE_BOTH);

		$sum = array_sum($sum);

		return $sum % 10;
	}
}