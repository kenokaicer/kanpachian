<?php 
namespace Models;

use Exception as Exception;

class File
{
	//public static function upload($file, $folder)
	public static function upload($file)
	{
		$path = null;
		//$folders_Array = array("images", "documents");
		$folderPath = IMG_PATH2;

		if (is_array($file) && !empty($file)) {

			//if (in_array($folder, $folders_Array)) {
			//	$folderPath = ROOT . 'assets/' . $folder . '/';

				if (!file_exists($folderPath)) {
					mkdir($folderPath);
				}

				if ($file['name']) {
					$fileTypes_Array = array('png', 'jpg', 'gif');
					$fileSize = 5000000;
					$fileName = $file['name'];

					$filePath = $folderPath . $fileName;

					$fileType = pathinfo($filePath, PATHINFO_EXTENSION);

					if (in_array($fileType, $fileTypes_Array)) {

						if ($file['size'] < $fileSize) {

							if (move_uploaded_file($file["tmp_name"], $filePath)) {
								$path = $fileName;

							} else throw new Exception("Error when moving the file.");
						} else throw new Exception("Error, the allowed size was exceeded.");
					} else throw new Exception("Error, file format not allowed.");
				} else throw new Exception("Error, give the file a name.");
			//} else throw new Exception("Error, wrong destination folder.");
		} else throw new Exception("Error, the variable is not a file");

		return $path;
	}
}

?>