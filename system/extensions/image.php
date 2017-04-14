<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 08/04/2017
 * License: GPLv3
 */

/*
 * This file is part of uweb.
 *
 * uWeb - uCodev Low Footprint Web Framework (https://github.com/ucodev/uweb)
 * Copyright (C) 2014-2017  Pedro A. Hortas
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class UW_Image {
	public function resize($orig, $dest, $width, $height = -1, $quality = 86, $mode = 'bicubic') {
		switch ($mode) {
			case 'resampled': return $this->_resize_gd_resampled($orig, $dest, $width, $height, $quality);
			case 'bilinear-fixed': return $this->_resize_gd($orig, $dest, $width, $height, $quality, IMG_BILINEAR_FIXED);
			case 'nearest-neighbour': return $this->_resize_gd($orig, $dest, $width, $height, $quality, IMG_NEAREST_NEIGHBOUR);
			case 'bicubic-fixed': return $this->_resize_gd($orig, $dest, $width, $height, $quality, IMG_BICUBIC_FIXED);
			case 'bicubic': return $this->_resize_gd($orig, $dest, $width, $height, $quality, IMG_BICUBIC);
			case 'lanczos': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_LANCZOS);
			case 'point': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_POINT);
			case 'box': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_BOX);
			case 'triangle': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_TRIANGLE);
			case 'hermite': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_HERMITE);
			case 'hanning': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_HANNING);
			case 'hamming': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_HAMMING);
			case 'blackman': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_BLACKMAN);
			case 'gaussian': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_GAUSSIAN);
			case 'quadratic': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_QUADRATIC);
			case 'cubic': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_CUBIC);
			case 'catrom': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_CATROM);
			case 'mitchell': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_MITCHELL);
			case 'bessel': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_BESSEL);
			case 'sinc': return $this->_resize_imagick($orig, $dest, $width, $height, $quality, Imagick::FILTER_SINC);
			default: break;
		}

		return false;
	}

	public function file_extension($filename) {
		$a = explode('.', $filename);

		if (!$a) return '';

		$ext = end($a);

		return strtolower($ext);
	}

	private function _resize_imagick($orig, $dest, $width, $height = -1, $quality = 86, $filter = Imagick::FILTER_LANCZOS) {
		/* Normalize $height omission */
		if ($height == -1) $height = 0;

		/* Check if the path is valid and the file exists */
		if (($orig_path = realpath($orig)) === false) {
			error_log(__FUNCTION__ . '(): Image file not found: ' . $orig);
			return false;
		}

		/* Create Imagick object from file */
		try {
			$imagick = new \Imagick(realpath($orig_path));
		} catch (ImagickException $e) {
			error_log(__FUNCTION__ . '(): Cannot load image: ' . $orig_path);
			return false;
		}

		/* Set the compression level for the output image */
		try {
			$imagick->setImageCompressionQuality($quality);
		} catch (Exception $e) {
			error_log(__FUNCTION__ . '(): Cannot set compression quality: ' . $orig_path);
			return false;
		}

		/* Get current image dimensions and compute the destination dimensions */
		try {
			$width_orig = $imagick->getImageWidth();
			$height_orig = $imagick->getImageHeight();
		} catch (Exception $e) {
			error_log(__FUNCTION__ . '(): Cannot retrieve image width and height: ' . $orig_path);
			return false;
		}

		$width_dest = $width;
		$height_dest = $height;

		/* Resize image */
		try {
			$imagick->resizeImage($width_dest, $height_dest, $filter, 1);
		} catch (Exception $e) {
			error_log(__FUNCTION__ . '(): Unable to resize image to (' . $width_dest . 'x' . $height_dest . '): ' . $orig_path);
			return false;
		}

		/* Store resized image */
		try {
			$imagick->writeImage($dest);
		} catch (Exception $e) {
			error_log(__FUNCTION__ . '(): Unable to write resized image: ' . $dest);
			return false;
		}

		/* Destroy Imagick object */
		$imagick->destroy();

		/* All good */
		return true;
	}

	private function _resize_gd_resampled($orig, $dest, $width, $height = -1, $quality = 86) {
		/* Check if the path is valid and the file exists */
		if (($orig_path = realpath($orig)) === false) {
			error_log(__FUNCTION__ . '(): Image file not found: ' . $orig);
			return false;
		}

		/* Load image file */
		if (($imgdata = file_get_contents($orig_path)) === false) {
			error_log(__FUNCTION__ . '(): Unable to read file contents: ' . $orig);
			return false;
		}

		/* Create image resource file from image contents */
		if (($imgrc_orig = imagecreatefromstring($imgdata)) === false) {
			error_log(__FUNCTION__ . '(): Image type is unsupported, the data is not in a recognised format, or the image is corrupt and cannot be loaded: ' . $orig);
			return false;
		}

		/* Get image width */
		if (($width_orig = imagesx($imgrc_orig)) === false) {
			error_log(__FUNCTION__ . '(): Cannot determine image width: ' . $orig);
			return false;
		}

		/* Avoid divide by zero */
		if ($width_orig == 0) {
			error_log(__FUNCTION__ . '(): Got a zero width for image: ' . $orig);
			return false;
		}

		/* Get image height */
		if (($height_orig = imagesy($imgrc_orig)) === false) {
			error_log(__FUNCTION__ . '(): Cannot determine image height: ' . $orig);
			return false;
		}

		/* Set destination width and heigth */
		$width_dest = $width;

		if ($height == -1)
			$height = intval($height_orig * ($width_dest / $width_orig));

		$height_dest = $height;

		/* Create a true color image resource */
		if (($imgrc_dest = imagecreatetruecolor($width_dest, $height_dest)) === false) {
			error_log(__FUNCTION__ . '(): Unable to create a true color image resource: ' . $dest);
			return false;
		}

		//imageantialias($imgrc_dest, true);

		if (imagecopyresampled($imgrc_dest, $imgrc_orig, 0, 0, 0, 0, $width_dest, $height_dest, $width_orig, $height_orig) === false) {
			error_log(__FUNCTION__ . '(): Unable to resample image: ' . $dest);
			return false;
		}

		/* Output format will match the original file extension */
		switch ($this->file_extension($orig)) {
			case 'jpeg':
			case 'jpg': {
				if (imagejpeg($imgrc_dest, $dest, $quality) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image JPG file: ' . $dest);
					return false;
				}
			} break;
			case 'png': {
				if (imagepng($imgrc_dest, $dest, $quality) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image file: ' . $dest);
					return false;
				}
			} break;
			case 'gif': {
				if (imagegif($imgrc_dest, $dest) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image file: ' . $dest);
					return false;
				}
			} break;
			case 'bmp': {
				if (imagebmp($imgrc_dest, $dest) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image file: ' . $dest);
					return false;
				}
			} break;
			default: {
				error_log('Unsupported file format: ' . $orig);
				return false;
			}
		}

		/* Destroy image resources */
		imagedestroy($imgrc_orig);
		imagedestroy($imgrc_dest);

		/* All good */
		return true;
	}

	private function _resize_gd($orig, $dest, $width, $height = -1, $quality = 86, $mode = IMG_BICUBIC) {
		/* Check if the path is valid and the file exists */
		if (($orig_path = realpath($orig)) === false) {
			error_log(__FUNCTION__ . '(): Image file not found: ' . $orig);
			return false;
		}

		/* Load image file */
		if (($imgdata = file_get_contents($orig_path)) === false) {
			error_log(__FUNCTION__ . '(): Unable to read file contents: ' . $orig);
			return false;
		}

		/* Create image resource file from image contents */
		if (($imgrc_orig = imagecreatefromstring($imgdata)) === false) {
			error_log(__FUNCTION__ . '(): Image type is unsupported, the data is not in a recognised format, or the image is corrupt and cannot be loaded: ' . $orig);
			return false;
		}

		/* Scale image to target dimensions */
		if (($imgrc_dest = imagescale($imgrc_orig, $width, $height, $mode)) === false) {
			error_log(__FUNCTION__ . '(): Failed to resize image to (' . $width . 'x' . $height . '): ' . $orig);
			return false;
		}

		/* Output format will match the original file extension */
		switch ($this->file_extension($orig)) {
			case 'jpeg':
			case 'jpg': {
				if (imagejpeg($imgrc_dest, $dest, $quality) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image JPG file: ' . $dest);
					return false;
				}
			} break;
			case 'png': {
				if (imagepng($imgrc_dest, $dest, $quality) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image file: ' . $dest);
					return false;
				}
			} break;
			case 'gif': {
				if (imagegif($imgrc_dest, $dest) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image file: ' . $dest);
					return false;
				}
			} break;
			case 'bmp': {
				if (imagebmp($imgrc_dest, $dest) === false) {
					error_log(__FUNCTION__ . '(): Unable to create resized image file: ' . $dest);
					return false;
				}
			} break;
			default: {
				error_log('Unsupported file format: ' . $orig);
				return false;
			}
		}

		/* Destroy image resources */
		imagedestroy($imgrc_orig);
		imagedestroy($imgrc_dest);

		/* All good */
		return true;
	}
}
