<?php
/**
*	class Image
*
*	utworzenie obiektu klasy Image
*	$img = new Image(string ŚcieżkaDoPliku);								// grafika typu: GIF, JPG lub PNG
*
*	$img->SetWidth(int NowaSzerokość [, bool ZachowajProporcje = true]);	// zmiana szerokości grafiki
*	$img->SetHeight(int NowaWysokość [, bool ZachowajProporcje = true]);	// zmiana wysokości grafiki
*	$img->SetSize(int NowaSzerokość ,int NowaWysokość);						// zmiana wymiarów (szerokości i wysokości) grafiki
*	$img->SetMaxSize(int MaksymalnyWymiar);									// ustawienie dłuższego boku grafiki
*	$img->Save(string NazwaPliku)											// zapis grafiki do pliku
*	$img->Send()															// wysłanie grafiki do przeglądarki
*/

class Image
{	var $_img;				// image handler
	var $_src_file;			// image file
	var $_img_info;			// image info - array()

	var $_dest_type = "jpg";				// image type
	var $_dest_width = 0;					// new image width
	var $_dest_height = 0;					// new image height

	/**
	 * Class constructor
	 * @param string	$sFileName - image file name
	 * @return void
	 */
	public function __construct($sFileName) 
	{	$this->_img_info = @getimagesize($sFileName);
		if (!is_array($this->_img_info))		return false;
		// $_img_info[2]: 1 = GIF, 2 = JPG, 3 = PNG
		switch ($this->_img_info[2])
		{	case 1:		$this->_img = @ImageCreateFromGIF($sFileName);		break;
			case 2:		$this->_img = @ImageCreateFromJPEG($sFileName);		break;
			case 3:		$this->_img = @ImageCreateFromPNG($sFileName);		break;
			default:	return false;
		}
		return $this->_img;
	}

	/**
	 * Method to set image type
	 * @param string $sDestType - image type
	 * @return void
	 */
	public function SetType($sDestType)
	{	$sDestType = strtolower($sDestType);
		if ($sDestType == "gif" || $sDestType == "png" || $sDestType == "jpg")
				$this->_dest_type = $sDestType;
	}

	/**
	 * Method to set new image width
	 * @param int	$iNewWidth - new image width
	 * @param bool	$bProportional - proportional resize
	 * @return void
	 */
	public function SetWidth($iNewWidth, $bProportional = 1)
	{	$this->_dest_width = $iNewWidth;
		if ($bProportional)
		{	$this->_dest_height = intval($this->_img_info[1] * ($iNewWidth / $this->_img_info[0]));
		}
	}
	
	/**
	 * Method to set new image height
	 * @param int	$iNewHeight - new image height
	 * @param bool	$bProportional - proportional resize
	 * @return void
	 */
	public function SetHeight($iNewHeight, $bProportional = 1)
	{	$this->_dest_height = $iNewHeight;
		if ($bProportional)
		{	$this->_dest_width = intval($this->_img_info[0] * ($iNewHeight / $this->_img_info[1]));
		}
	}
	
	/**
	 * Method to set new image size
	 * @param int	$iNewWidth - width
	 * @param int	$iNewHeight - height
	 * @return void
	 */
	function SetSize($iNewWidth, $iNewHeight)
	{	$this->_dest_width = $iNewWidth;
		$this->_dest_height = $iNewHeight;
	}

	/**
	 * Method to set scale new image
	 * @param int	$iMaxSize - max dimension (height or width)
	 * @return void
	 */
	public function SetMaxSize($iMaxSize)
	{	if (!is_int($iMaxSize))		return false;
		if ($iMaxSize <= 0)			return false;
		if($this->_img_info[0] <= $iMaxSize && $this->_img_info[1] <= $iMaxSize)		return true;
		if($this->_img_info[0] > $this->_img_info[1])		//szerszy ni wyszy
		{	$this->_dest_width = $iMaxSize;
			$this->_dest_height = intval($this->_img_info[1] * ($iMaxSize / $this->_img_info[0]));
		}
		else		//wyszy niz szerszy
		{	$this->_dest_height = $iMaxSize;
			$this->_dest_width = intval($this->_img_info[0] * ($iMaxSize / $this->_img_info[1]));
		}
	}

	/**
	 * Save image into file
	 * @param string	$sFileName - path to file
	 * @return void
	 */
	public function Save($sFileName)
	{	if (!is_string($sFileName))			return false;

		if($this->_dest_width == 0 )		$this->_dest_width = $this->_img_info[0];
		if($this->_dest_height == 0 )		$this->_dest_height = $this->_img_info[1];

		$img_new = ImageCreateTrueColor($this->_dest_width, $this->_dest_height);
		ImageCopyResampled($img_new, $this->_img, 0, 0, 0, 0, $this->_dest_width, $this->_dest_height, $this->_img_info[0], $this->_img_info[1]);

		if ($this->_dest_type == "jpg" || $this->_dest_type == "jpeg")	@ImageJPEG($img_new, $sFileName);
		elseif ($this->_dest_type == "gif")								@ImageGIF($img_new, $sFileName);
		else															@ImagePNG($img_new, $sFileName);
	}
	

	/**
	 * Send image to browser
	 * @param void
	 * @return void
	 */
	public function Send()
	{	if($this->_dest_width == 0 )		$this->_dest_width = $this->_img_info[0];
		if($this->_dest_height == 0 )		$this->_dest_height = $this->_img_info[1];
		
		$img_new = ImageCreateTrueColor($this->_dest_width, $this->_dest_height);
		ImageCopyResampled($img_new, $this->_img, 0, 0, 0, 0, $this->_dest_width, $this->_dest_height, $this->_img_info[0], $this->_img_info[1]);

		$this->_dest_type = ($this->_dest_type=="jpg") ? "jpeg" : $this->_dest_type;
		$fun = "image".$this->_dest_type;
		
		header('Content-type: '.$this->_img_info["mime"]);
		$fun($img_new);
        imagedestroy($this->img);
        return;
	}

	/**
	 * Get image type
	 * @param void
	 * @return string - image mime type
	 */
	public function GetImgType()
	{	return $this->_img_info["mime"];
	}

	/**
	 * Get image width
	 * @param void
	 * @return int - current image width
	 */
	public function GetImgWidth()
	{	return $this->_dest_width;
	}

	/**
	 * Get image height
	 * @param void
	 * @return int - current image height
	 */
	public function GetImgHeight()
	{	return $this->_dest_height;
	}

	/**
	 * Get attributes HEIGHT and WIDTH for tag <IMG>
	 * @param void
	 * @return string - <IMG> attributes
	 */
	public function GetImgHTML()
	{	return $this->_img_info[3];
	}

	/**
	 * Class destructor
	 * @param void
	 * @return void
	 */
	public function __destruct()
	{	imagedestroy($this->_img);
	}//end function
}//end class
?>
