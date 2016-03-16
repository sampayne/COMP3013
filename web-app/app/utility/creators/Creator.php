<?php declare(strict_types=1);

    namespace App\Utility\Creator;

    use \Aws\S3\S3Client as S3Client;

    use DateTime as DateTime;

    abstract class Creator {

        protected static $image_directory = NULL;

        protected static $public_image_directory = 'images';

        protected $current_input = [];

        protected static $image_mime_types = ['image/jpeg', 'image/jpg', 'image/png'];

        public abstract function saveInput(array $input) : int;

        public abstract function validateInput(array $input) : array;

        public function __construct($request){

            if(is_null(self::$image_directory)){

                self::$image_directory = $request->server['DOCUMENT_ROOT'].self::$public_image_directory;

            }
        }

        protected function isPresent(string $key) : bool {

            return isset($this->current_input[$key]);
        }

        protected function saveImage(array $file, string $subdirectory = '') : string {

            $s3 = new S3Client([
                'version' => 'latest',
                'region'  => 'eu-west-1']);

            $subdirectory =  trim($subdirectory,'/');

            list($name, $extension) = explode('.', trim($file['name'],'/'));

            $filename = md5((string) rand()).'.'.$extension;

            $directory = self::$public_image_directory.'/'.$subdirectory.'/';

            $bucket_name = 'comp3013';


           if(!$s3->doesBucketExist($bucket_name)){
               echo 'Error, bucket didnt exist';
               exit(0);

           };

            while ($s3->doesObjectExist($bucket_name, $directory.$filename)) {
                $filename = md5((string) rand()).'.'.$extension;
            }

            $parameters = [
                'ContentType'=>$file['type'],
    			'Bucket'     => $bucket_name,
    			'Key' => $directory.$filename,
    			'SourceFile' => $file['tmp_name']
    		];

    		print_r($parameters);

    		$s3->putObject($parameters);

    		$s3->waitUntil('ObjectExists', [
    			'Bucket' => $bucket_name,
    			'Key'    => $directory.$filename
    		]);

    		//exit(0);

            return '//comp3013.s3-website-eu-west-1.amazonaws.com/'.$directory.$filename;
        }

        protected function isValidDate(string $key) : bool {

            if($this->isNonEmptyString($key)){

                $format = 'Y-m-d H:i:s';

                $date = $this->current_input[$key];

                // http://php.net/manual/en/function.checkdate.php#113205

                $d = DateTime::createFromFormat($format, $date);

                return $d && $d->format($format) == $date;

            }

            return false;
        }

        protected function isFile(string $key) : bool {

            if ($this->isArray($key)){

                $file = $this->current_input[$key];

                return isset($file['tmp_name']) && is_uploaded_file($file['tmp_name']);
            }

            return false;
        }

        protected function isArrayOfMinLength(string $key, int $length){

            return $this->isArray($key) && count($this->current_input[$key]) >= $length;
        }

        protected function isArray(string $key) : bool {

            return $this->isPresent($key) && is_array($this->current_input[$key]);
        }

        protected function isImageFile(string $key) : bool {

            if($this->isFile($key)){

                $file = $this->current_input[$key];

                $file_type = $file['type'];

                return in_array($file_type , self::$image_mime_types);

            }

            return false;
        }

        protected function isGreaterThan(string $key, $value){

            return $this->isPresent($key) && $this->current_input[$key] > $value;

        }

        protected function isString(string $key) : bool {

            return $this->isPresent($key) && is_string($this->current_input[$key]);
        }

        protected function isNonEmptyString(string $key) : bool {

            return $this->isString($key) && $this->current_input[$key] !== '';
        }

        protected function isNumeric(string $key) : bool {

            return $this->isPresent($key) && is_numeric($this->current_input[$key]);
        }
    }