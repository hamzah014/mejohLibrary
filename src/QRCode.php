<?php

namespace MejohLibrary;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Alignment\LabelAlignmentLeft;
use Endroid\QrCode\Label\Alignment\LabelAlignmentRight;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Exception;


class QRCode
{
    protected $builder;
    protected $data;

    public function __construct()
    {

    }

    public function generate()
    {
        $this->builder = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin());

        return $this;

    }

    public function setData(string $data)
    {
        $this->data = $data;
        $this->builder->data($data);

        return $this;
    }

    public function setForegroundColor(string $hexColor)
    {
        $this->builder->foregroundColor( $this->getColor($hexColor) );

        return $this;
    }

    public function setBackgroundColor(string $hexColor)
    {
        $this->builder->backgroundColor( $this->getColor($hexColor) );

        return $this;
    }

    /**
     * @position string Position : center, right, left.(default:left)
     */
    public function setLabel(string $text, string $hexColor, int $fontSize, string $position)
    {
        $this->builder->labelText($text)
        ->labelTextColor( $this->getColor($hexColor) )
        ->labelFont(new NotoSans($fontSize))
        ->labelAlignment( $this->getLabelAlignment($position) );

        return $this;
    }

    public function setSize(int $size)
    {
        $this->builder->size($size);

        return $this;
    }
    
    public function setPadding(int $size)
    {
        $this->builder->margin($size);

        return $this;
    }

    public function buildUri()
    {
        $this->validate();

        $result = $this->builder->validateResult(false)
        ->build();
        $dataUri = $result->getDataUri();

        return $dataUri;
    }

    public function build()
    {
        $this->validate();

        $result = $this->builder->validateResult(false)
        ->build();
        $dataUri = $result->getString();

        return $dataUri;

    }

    private function getColor($hex)
    {
        // Remove the hash (#) if present
        $hex = ltrim($hex, '#');
        
        // If it's a 3-character hex code, expand it to 6 characters
        if (strlen($hex) == 3) {
            $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
        }
        
        // Convert the hex string to RGB values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Create a new Color object using Endroid\QrCode\Color\Color
        return new Color($r, $g, $b);

    }

    private function getLabelAlignment($code)
    {
        $align = null;

        switch ($code) {
            case 'center':
                $align = new LabelAlignmentCenter();
                break;

            case 'right':
                $align = new LabelAlignmentRight();
                break;
            
            default:
                $align = new LabelAlignmentLeft();
                break;
        }

        return $align;

    }

    private function validate()
    {
        if(!isset($this->data))
        {
            throw new Exception("Data is not set.");
        }
    }

}
