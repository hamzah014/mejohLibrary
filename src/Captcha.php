<?php

namespace MejohLibrary;

class Captcha
{
    private $width;
    private $height;
    private $font;
    private $text;

    public function __construct($width = 200, $height = 50)
    {
        $font = 'arial.ttf';
        $this->width = $width;
        $this->height = $height;
        $this->font = __DIR__ . "/font" . "/" . $font;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function generate()
    {
        
        // Create a blank image
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

        $fontSize = 20;
        $angle = 0;
        $x = 10;
        $y = $this->height - 10; // Adjust the y position for better alignment

        imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $this->font, $this->text);

        // Output the image to memory
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        // Free up memory
        imagedestroy($image);

        return $imageData;

    }

    public function generateBase64()
    {
        $imageData = $this->generate();

        $base64 = base64_encode($imageData);

        return $base64;

    }

}
