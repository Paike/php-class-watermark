<?php
/**
* @description	Modifies a watermark image and places it on another image
* @version		1.0
* @author		Patrick Prädikow
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.*
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/
class Watermark 
{
	/**
	* @var string $imagePath
	*/
	private $imagePath;
	
	/**
	* @var string $watermarkPath
	*/
	private $watermarkPath;
	
	/**
	* @var string $destinationPath
	*/
	private $destinationPath;
	
	/**
	* @var string $backupPath
	*/
	private $backupPath;
	
	/**
	* @var float $opacity
	*/
	private $opacity = 0.3;
	
	/**
	* @var string or integer $width (33 || '33%')
	*/
	private $width = '80%';
	
	/**
	* @var string or integer $height (33 || '33%')
	*/
	private $height = NULL;
	
	/**
	* @var string $alignX
	*/
	private $alignX = 'center';
	
	/**
	* @var string $alignY
	*/
	private $alignY = 'middle';
	
	/**
	* @var string or integer $offsetX (33 || '33%')
	*/
	private $offsetX = 0;
	
	/**
	* @var string or integer $offsetY (33 || '33%')
	*/
	private $offsetY = 0;
	
	/**
	* @var string $outputFormat ('jpg','png','gif')
	*/
	private $outputFormat = 'jpg';
	
	/**
	* @var integer $quality
	*/
	private $quality = 100;
	
	/**
	* @var boolean $debug
	*/
	private $debug = false;
	
	/**
	* @var array $error
	*/
	private $error;
	
	/**
	* Set original image path
	*
	* @return	string	The path to image file
	*
	* @throws \Exception when the original image is not found
	*/
	public function setOriginal($imagePath)
	{
		try {
			if(!file_exists($imagePath)) throw new Exception('Original image not found in '.$imagePath);
			if(!$this->isImage($imagePath)) throw new Exception('Original image is no image file in '.$imagePath);
			$this->imagePath = $imagePath;
		} catch(Exception $e) {
			$this->error[] = $e->getMessage(); 
		}
		
		return $this;
	}

	/**
	* Set watermark image path
	*
	* @return	string	The path to watermark file
	*
	* @throws \Exception when the watermark image is not found
	*/
	public function setWatermark($watermarkPath)
	{
		try {
			if(!file_exists($watermarkPath)) throw new Exception('Watermark image not found in '.$watermarkPath);
			if(!$this->isImage($watermarkPath)) throw new Exception('Watermark image is no image file in '.$watermarkPath);
			$this->watermarkPath = $watermarkPath;
		} catch(Exception $e) {
			$this->error[] = $e->getMessage(); 
		}
		
		return $this;
	}
	
	/**
	* Set modified image destination path
	*
	* @return	string	The path to save the modified image in
	*
	* @throws \Exception when folder is not writable or not found
	*/
	public function setDestinationPath($destinationPath)
	{
		try {
			if(!file_exists($destinationPath)) throw new Exception('Destination path does not exist in '.$destinationPath);
			if(!is_writable($destinationPath)) throw new Exception('Destination path is not writable in '.$destinationPath);
		} catch(Exception $e) {
			$this->error[] = $e->getMessage(); 
		}		
		
		$this->destinationPath = $destinationPath;
		return $this;
	}
	
	/**
	* Set destination filename
	*
	* @return	string	The name of modified image file
	*/
	public function setDestinationFilename($destinationFilename)
	{
		$this->destinationFilename = $destinationFilename;
		return $this;
	}
	
	/**
	* Set backup path
	*
	* @return	string	The path to save the backupped image in
	*
	* @throws \Exception when folder is not writable or not found
	*/
	public function setBackupPath($backupPath)
	{
		try {
			if(!file_exists($backupPath)) throw new Exception('Backup path does not exist in '.$backupPath);
			if(!is_writable($backupPath)) throw new Exception('Backup path is not writable in '.$backupPath);
			if(pathinfo($this->imagePath, PATHINFO_DIRNAME) == $backupPath) throw new Exception('Origin path ' . pathinfo($this->imagePath, PATHINFO_DIRNAME) . ' and backup path '.$backupPath . ' cannot be the same');
			
		} catch(Exception $e) {
			$this->error[] = $e->getMessage(); 
		}		
		
		$this->backupPath = $backupPath;
		return $this;
	}
	
	/**
	* Set watermark opacity
	*
	* @return	float	The opacity value (0...1)
	*/	
	public function setOpacity($opacity = 1)
	{
		$this->opacity = (float)$opacity;
		return $this;
	}
	
	/**
	* Set watermark width
	*
	* @return	string	Can be integer (300) or percentage ('33%')
	*/
	public function setWidth($width = NULL)
	{
		$this->width = $width;
		return $this;
	}
	
	/**
	* Set watermark height
	*
	* @return	string	Can be integer (300) or percentage ('33%')
	*/
	public function setHeight($height = NULL)
	{
		$this->height = $height;
		return $this;
	}

	/**
	* Set watermark horizontal alignment
	*
	* @return	string	Can be 'left', 'center', 'right'
	*/	
	public function setAlignX($alignX = 'center')
	{
		$this->alignX = $alignX;
		return $this;
	}

	/**
	* Set watermark vertical alignment
	*
	* @return	string	Can be 'top', 'middle', 'bottom'
	*/	
	public function setAlignY($alignY = 'middle')
	{
		$this->alignY = $alignY;
		return $this;
	}

	/**
	* Set watermark horizontal offset position
	*
	* @return	string	Can be integer (-+300) or percentage (-+'33%')
	*/	
	public function setOffsetX($offsetX = NULL)
	{
		$this->offsetX = $offsetX;
		return $this;
	}

	/**
	* Set watermark vertical offset position
	*
	* @return	string	Can be integer (-+300) or percentage (-+'33%')
	*/	
	public function setOffsetY($offsetY = NULL)
	{
		$this->offsetY = $offsetY;
		return $this;
	}

	/**
	* Set modified file output format
	*
	* @return	string	Can be 'jpg','png','gif'
	*/	
	public function setOutputFormat($outputFormat = 'jpg')
	{
		if(empty($outputFormat)) $outputFormat = 'jpg';
		try {
			if (!in_array(strtolower($outputFormat), array('jpg','jpeg','png','gif'))) throw new Exception('Output format is unknown: '.$outputFormat);
		} catch(Exception $e) {
			$this->error[] = $e->getMessage(); 
		}		
		$this->outputFormat = $outputFormat;
		return $this;
	}

	/**
	* Set modified file image quality
	*
	* @return	integer	Can be 0...100
	*/	
	public function setQuality($quality = 100)
	{
		try { 
			if (!is_numeric($quality)) throw new Exception('SetQuality - is not an integer:'.$quality);
			if ($quality < 0 || $quality > 100) throw new Exception('SetQuality - must be between 0 and 100:'.$quality);
		} catch(Exception $e) {
			$this->error[] = $e->getMessage(); 
		}	

		$this->quality = (int)$quality;
		return $this;
	}

	/**
	* Set debug mode
	*
	* @return	boolean	Prints error message when true
	*/		
	public function setDebug($debug = true)
	{
		$this->debug = $debug;
		return $this;
	}
	
	/**
	* Modifies the watermark image and its dimensions according to user specified settings
	*
	* @return	imageresource	The modified image resource
	*/
	
	private function createImageResource()
	{
		$imageResource = $this->imageCreateFrom($this->imagePath);
		$watermarkResource 	 = $this->imageCreateFrom($this->watermarkPath);
		$watermarkResource   = $this->imageSetOpacity($watermarkResource,$this->opacity);

		$originalImageWidth  = imagesx($imageResource);
		$originalImageHeight = imagesy($imageResource);

		$watermarkWidth  = imagesx($watermarkResource);
		$watermarkHeight = imagesy($watermarkResource);	

		if(strpos($this->offsetX, '%') !== false) {
			$this->offsetX = str_replace('%','',$this->offsetX);
			$this->offsetX = $originalImageWidth / 100 * $this->offsetX;
		}
		
		if(strpos($this->offsetY, '%') !== false) {
			$this->offsetY = str_replace('%','',$this->offsetY);
			$this->offsetY = $originalImageHeight / 100 * $this->offsetY;
		}
	// Landscape
		if($watermarkHeight < $watermarkWidth ) {
			
			if(empty($this->width)) $this->width = $watermarkWidth;
			
			if(strpos($this->width, '%') !== false) {
				$this->width = str_replace('%','',$this->width);
				$width = $originalImageWidth * (int)$this->width / 100;
			} else {
				$width = (int)$this->width;
			}

			if(empty($this->height)) {
				$height = (($watermarkHeight * $width) / $watermarkWidth);
			}
			
			if(strpos($this->height, '%') !== false) {
				$this->height = str_replace('%','',$this->height);
				$height = $originalImageHeight * (int)$this->height / 100;
			} elseif(!empty($this->height)) {
				$height = (int)$this->height;
			}
			
			
			
			
			if($this->alignX == 'left') {
				$alignX = 0 + $this->offsetX;
			}
			if($this->alignX == 'center') {
				$alignX = ( $originalImageWidth / 2 ) - ( $width / 2 ) + $this->offsetX;
			}
			if($this->alignX == 'right') {
				$alignX = $originalImageWidth - $width + $this->offsetX;
			}
			
			if($this->alignY == 'top') {
				$alignY = 0 + $this->offsetY;
			}	
			if($this->alignY == 'middle') {
				$alignY = ( $originalImageHeight / 2 ) - ( $height / 2 ) + $this->offsetY;
			}
			if($this->alignY == 'bottom') {
				$alignY = $originalImageHeight - $height + $this->offsetY;
			}
		}
	
	// Portrait
		if($watermarkHeight > $watermarkWidth ) {

			if(empty($this->height)) $this->height = $watermarkHeight;
			if(strpos($this->height, '%') !== false) {
				$this->height = str_replace('%','',$this->height);
				$height = $originalImageHeight * (int)$this->height / 100;

			} else {
				$height = (int)$this->height;
			}

			if(empty($this->width)) {
				$width = (($watermarkWidth * $height) / $watermarkHeight);
			}
			
			if(strpos($this->width, '%') !== false) {
				$this->width = str_replace('%','',$this->width);
				$width = $originalImageWidth * (int)$this->width / 100;
			} elseif(!empty($this->width)) {
				$width = (int)$this->width;
			}
			
			if($this->alignX == 'left') {
				$alignX = 0 + $this->offsetX;
			}
			
			if($this->alignX == 'center') {
				$alignX = ( $originalImageWidth / 2 ) - ( $width / 2 ) + $this->offsetX;
			}
			if($this->alignX == 'right') {
				$alignX = $originalImageWidth - $width + $this->offsetX;
			}
			
			if($this->alignY == 'top') {
				$alignY = 0 + $this->offsetY;
			}

			if($this->alignY == 'middle') {
				$alignY = ( $originalImageHeight / 2 ) - ( $height / 2 ) + $this->offsetY;
			}
			if($this->alignY == 'bottom') {
				$alignY = $originalImageHeight - $height + $this->offsetY;
			}
		}
	
		imagecopyresized($imageResource,$watermarkResource,$alignX,$alignY,0,0,$width,$height,$watermarkWidth,$watermarkHeight);
		imagedestroy($watermarkResource);
		
		return $imageResource;
	}
	
	/**
	*	Returns an image resource with alpha-opacitized pixels
	*
	*	I like this -- credits to 
	*	Surgie / FinesseRus (https://github.com/FinesseRus)
	*	http://stackoverflow.com/a/33818635
	*	
	*	@param	imageresource	$imageSrc	imageCreateFrom()
	*	@param	float			$opacity	user specified option
	*
	*	@return imageresource	the opacitized imageresource
	*/
	
	private function imageSetOpacity( $imageSrc, $opacity )
	{
		$width  = imagesx( $imageSrc );
		$height = imagesy( $imageSrc );

		// Duplicate image and convert to TrueColor
		$imageDst = imagecreatetruecolor( $width, $height );
		imagealphablending( $imageDst, false );
		imagefill( $imageDst, 0, 0, imagecolortransparent( $imageDst ) );
		imagecopy( $imageDst, $imageSrc, 0, 0, 0, 0, $width, $height );

		// Set new opacity to each pixel
		for ( $x = 0; $x < $width; ++$x )
			for ( $y = 0; $y < $height; ++$y ) {
				$color = imagecolorat( $imageDst, $x, $y );
				$alpha = 127 - ( ( $color >> 24 ) & 0xFF );
				if ( $alpha > 0 ) {
					$color = ( $color & 0xFFFFFF ) | ( (int)round( 127 - $alpha * $opacity ) << 24 );
					imagesetpixel( $imageDst, $x, $y, $color );
				}
			}
		return $imageDst;
	}

	/**
	* Creates an imageresource depending on the input mime type
	*
	* @param	string	$imagePath	The full file path to the image
	*
	* @return	imageresource	The imageresource
	*/
	
	private function imageCreateFrom($imagePath) 
	{
		
		$imageInfo = getimagesize($imagePath);
		$imageType = $imageInfo[2];

		switch($imageType){ //determine uploaded image type 
            //Create new image from file
            case IMAGETYPE_PNG: 
                $imageResource =  imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $imageResource =  imagecreatefromgif($imagePath);
                break;          
            case IMAGETYPE_JPEG: case 'image/pjpeg':
                $imageResource = imagecreatefromjpeg($imagePath);
                break;
            default:
                $imageResource = false;
        }
		return $imageResource;
	}	
	
	/**
	* Saves the watermarked image in the filesystem
	*/
	
	public function save()
	{
		if(!empty($this->error)) {
			if($this->debug === true) print_r($this->error);
			return;
		}
		
		$imageResource = $this->createImageResource();
		
		if(empty($this->destinationFilename)) {
			$filename = basename($this->imagePath,pathinfo($this->imagePath, PATHINFO_EXTENSION));
		} else {
			$filename = $this->destinationFilename . '.';
		}
		
		if(empty($this->destinationPath)) {
			$path = pathinfo($this->imagePath, PATHINFO_DIRNAME);
		} else {
			$path = $this->destinationPath;
		}
		
		if(!empty($this->backupPath)) {
			$backupFile = basename($this->imagePath);
			
			if(!copy($this->imagePath,$this->backupPath.'/'.$backupFile)) throw new Exception('Could not copy file ' .$this->imagePath . ' to ' . $this->backupPath.'/'.$backupFile);
		}
		
		//echo $path.'/'.$filename . 'jpg';
		switch($this->outputFormat){ //determine uploaded image type 
				//Create new image from file
				case 'png':
					//header('Content-Type: image/png');
					imagepng($imageResource, $path .'/'. $filename . 'png', $this->quality);
					break;
				case 'gif':
					//header('Content-Type: image/gif');
					imagegif($imageResource, $path .'/'. $filename . 'gif', $this->quality);
					break;
				default:
					//header('Content-Type: image/jpeg');
					imagejpeg($imageResource, $path .'/'. $filename . 'jpg', $this->quality);
					break;
			}
	}
	
	/**
	* Displays the watermarked image in the browser
	*/
	
	public function show()
	{
		if(!empty($this->error)) {
			if($this->debug === true) print_r($this->error);
			return;
		}
		
		$imageResource = $this->createImageResource();
		
		switch($this->outputFormat){ //determine uploaded image type 
				//Create new image from file
				case 'png':
					header('Content-Type: image/png');
					imagepng($imageResource, NULL , $this->quality);
					break;
				case 'gif':
					header('Content-Type: image/gif');
					imagegif($imageResource, NULL , $this->quality);
					break;
				default:
					header('Content-Type: image/jpeg');
					imagejpeg($imageResource, NULL , $this->quality);
					break;
		}
		imagedestroy($imageResource);
	}
	
	private function isImage($path)
	{
		$imageInfo = getimagesize($path);
		$imageType = $imageInfo[2];

		if(in_array($imageType , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG)))
		{
			return true;
		}
		return false;
	}
}