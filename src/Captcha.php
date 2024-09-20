<?php

namespace MejohLibrary;

class Captcha
{
    private $width;
    private $height;
    private $font;
    private $text;
    private $fontSize;
    private $generateText;
    private $filter;

    /**
     * Constructor to initialize the Captcha class.
     * 
     */
    public function __construct()
    {
        $width = 200;
        $height = 100;
        $fontSize = 20;
        $font = 'arial.ttf';

        $this->font = __DIR__ . "/font" . "/" . $font;
        $this->width = $width;
        $this->height = $height;
        $this->fontSize = $fontSize;
        $this->text = $this->randomText();
        $this->generateText = implode(' ', str_split($this->text));
        $this->filter = 'pxl';
    }

    /**
     * Function to start accessing the captcha generation.
     * 
     */
    public static function create()
    {
        return new self();
    }

    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the text for the captcha.
     *
     * @param string $text Text will be display in captcha.
     * 
     */
    public function setText($text)
    {
        $this->text = $text;
        $this->generateText = implode(' ', str_split($this->text));
        return $this;
    }

    /**
     * Set the width image for the captcha.
     *
     * @param int $width Size width will be display in captcha.
     * 
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Set the height image for the captcha.
     *
     * @param int $height Size height will be display in captcha.
     * 
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Set text font size for the captcha.
     *
     * @param int $size Text font size will be display in captcha.
     * 
     */
    public function setFontSize(int $fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }

    /**
     * Set filter for the captcha.
     *
     * @param string $filter Filter will be used in captcha.
     * 
     * OPTION : ["sct","pxl"]
     * DEFAULT : "pxl"
     * 
     */
    public function setFilter(string $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Generate the image.
     * 
     */
    public function generate()
    {

        $image = imagecreatetruecolor($this->width, $this->height);

        // Set background and text colors
        $bgColor = imagecolorallocate($image, 255, 255, 255); // white
        $textColor = imagecolorallocate($image, 0, 0, 0); // black

        // Fill the background
        imagefilledrectangle($image, 0, 0, $this->width, $this->height, $bgColor);

        // Add text to image
        if (!file_exists($this->font)) {
            throw new \Exception("Font file not found: " . $this->font);
        }

        $fontSize = $this->fontSize;
        $angle = 0;

        // Calculate text size and center position
        $textBox = imagettfbbox($fontSize, $angle, $this->font, $this->text);
        $textWidth = $textBox[2] - $textBox[0];  // width of the text
        $textHeight = $textBox[1] - $textBox[7]; // height of the text

        // Calculate X and Y positions to center the text
        $x = ($this->width - $textWidth) / 2;
        $y = ($this->height - $textHeight) / 2 + $textHeight;

        // Draw text on image
        imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $this->font, $this->generateText);

        $this->setupFilter($image);
        // Output the image to memory
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        // Free up memory
        imagedestroy($image);

        return $imageData;

    }

    /**
     * Generate the image base64 format.
     * 
     */
    public function generateBase64()
    {
        $imageData = $this->generate();

        $base64 = base64_encode($imageData);

        return $base64;

    }

    private function randomText()
    {
        $length = 5; $includeNumbers = true; $includeUppercase = true;

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Base character set (lowercase)
    
        // Optionally include numbers and uppercase letters
        if ($includeNumbers) {
            $characters .= '0123456789';
        }
        if ($includeUppercase) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        $captchaText = '';
        
        // Generate random text
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, strlen($characters) - 1); // Secure random integer
            $captchaText .= $characters[$randomIndex];
        }
        
        return $captchaText;

    }

    private function setupFilter($image)
    {

        switch ($this->filter) {
            case 'sct':
                imagefilter($image,  IMG_FILTER_SCATTER,6,8);
                break;
            
            default:
                imagefilter($image,  IMG_FILTER_PIXELATE,3,true);
                break;
        }

    }

}
