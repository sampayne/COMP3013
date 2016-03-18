<?php namespace App\Utility\Creator;

    use \Aws\S3\S3Client as S3Client;

    use DateTime as DateTime;

    abstract class Creator {

        protected static $image_directory = NULL;

        protected static $public_image_directory = 'images';

        protected $current_input = [];

        protected static $image_mime_types = ['image/jpeg', 'image/jpg', 'image/png'];

        public abstract function saveInput(array $input) ;

        public abstract function validateInput(array $input) ;

        public function __construct($request){

            if(is_null(self::$image_directory)){

                self::$image_directory = $request->server['DOCUMENT_ROOT'].self::$public_image_directory;

            }
        }

        protected function isPresent( $key)  {

            return isset($this->current_input[$key]);
        }

        protected function saveImage(array $file,  $subdirectory = '')  {

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

            return 'http://comp3013.s3-website-eu-west-1.amazonaws.com/'.$directory.$filename;
        }

        protected function isValidDate( $key)  {

            if($this->isNonEmptyString($key)){

                $format = 'Y-m-d H:i:s';

                $date = $this->current_input[$key];

                // http://php.net/manual/en/function.checkdate.php#113205

                $d = DateTime::createFromFormat($format, $date);

                return $d && $d->format($format) == $date;

            }

            return false;
        }

        protected function isFile( $key)  {

            if ($this->isArray($key)){

                $file = $this->current_input[$key];

                return isset($file['tmp_name']) && is_uploaded_file($file['tmp_name']);
            }

            return false;
        }

        protected function isArrayOfMinLength( $key,  $length){

            return $this->isArray($key) && count($this->current_input[$key]) >= $length;
        }

        protected function isArray( $key)   {

            return $this->isPresent($key) && is_array($this->current_input[$key]);
        }

        protected function isImageFile( $key)   {

            if($this->isFile($key)){

                $file = $this->current_input[$key];

                $file_type = $file['type'];

                return in_array($file_type , self::$image_mime_types);

            }

            return false;
        }

        protected function isGreaterThan( $key, $value){

            return $this->isPresent($key) && $this->current_input[$key] > $value;

        }

        protected function isString( $key)  {

            return $this->isPresent($key) && is_string($this->current_input[$key]);
        }

        protected function isNonEmptyString( $key)  {

            return $this->isString($key) && $this->current_input[$key] !== '';
        }

        protected function isNumeric( $key)  {

            return $this->isPresent($key) && is_numeric($this->current_input[$key]);
        }
    }