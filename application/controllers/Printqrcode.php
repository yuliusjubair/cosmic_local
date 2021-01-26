<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Treinetic\ImageArtist\lib\Image;
use Treinetic\ImageArtist\lib\Shapes\Triangle;
use Treinetic\ImageArtist\lib\Text\Write;
use Treinetic\ImageArtist\lib\Text;
use Treinetic\ImageArtist\lib\Text\TextBox;
use Treinetic\ImageArtist\lib\Text\Color;
use Treinetic\ImageArtist\lib\Text\Font;


class Printqrcode extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Printqrcode_model');
        $this->load->database();
        $this->load->helper(array('form', 'url','directory','path'));
        $this->load->library('mxencryption');
    }

    public function secure() {
        $this->session->set_userdata('redirect_url', current_url() );
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    
    public function index(){
        $mpm_id=2;
        $this->qrcode_bympmid($mpm_id);
    }
    
    public function enkrip(){
        $this->load->library('mx_encryption');
        $crypt = new mxencryption();
        $a = $crypt->encrypt('1');
        $b = $crypt->decrypt($a);
        var_dump($a);
        var_dump($b);
        die;
    }
    
    public function qrcode_bympmid($mpm_id){
        $list = $this->Printqrcode_model->printqrcode($mpm_id);
        
        $crypt = new mxencryption();
        $enc_mpmid = $crypt->encrypt(strval($mpm_id));
        
        $isi_qrcode  = 'http:'.base_url().'reportprotokol/report/'.$enc_mpmid;
        //var_export($isi_qrcode);die;
        $qrCode = new QrCode($isi_qrcode);
        $qrCode->setSize(400);
        $qrCode->setMargin(0);
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLabel('', 1, './vendor/endroid/qr-code/assets/fonts/noto_sans.otf', LabelAlignment::CENTER());
        $qrCode->setLogoPath('./uploads/cosmic.jpg');
        $qrCode->setLogoSize(120, 120);
        $qrCode->setValidateResult(false);

        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_MARGIN);
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_ENLARGE);
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_SHRINK);

        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        //header('Content-Type: '.$qrCode->getContentType());
        $qrCode->writeFile('./uploads/qrcode_mpm/qrcode_'.$mpm_id.'.jpg');
        $dataUri = $qrCode->writeDataUri();
    }
    
    function template($mpm_id){
        $this->qrcode_bympmid($mpm_id);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=qrmpm_$mpm_id.jpg");
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: binary/octet-stream");
        $datampm = $this->Printqrcode_model->printqrcode($mpm_id);
        
        $img = new Image('./uploads/bgqr.jpg');
        $img->resize(840,1188);
        $img2 = new Image('./uploads/qrcode_mpm/qrcode_'.$mpm_id.'.jpg');
        $img2->resize(380,380);
        $imgx = $img->merge($img2,($img->getWidth()-$img2->getWidth())/2,80);
        
        $textBox_scan = new TextBox(550,35);
        $textBox_scan->setColor(Color::getColor(Color::$BLACK));
        $textBox_scan->setFont(Font::getFont(Font::$NOTOSERIF_REGULAR));
        $textBox_scan->setSize(20);
        $textBox_scan->setMargin(2);
        $textBox_scan->setText("Scan untuk melihat informasi gedung ini");
        $imgx->setTextBox($textBox_scan,($img->getWidth()-$textBox_scan->getWidth())/2, 460, false);
       
        $textBox_mpm = new TextBox(700,100);
        $textBox_mpm->setColor(Color::getColor(Color::$BLUE_COSMIC));
        $textBox_mpm->setFont(Font::getFont(Font::$NOTOSERIF_REGULAR));
        $textBox_mpm->setSize(26);
        $textBox_mpm->setMargin(2);
        $textBox_mpm->setText($datampm->mpm_name);
        $imgx->setTextBox($textBox_mpm,($img->getWidth()-$textBox_mpm->getWidth())/2, 520, false);
        
        $textBox_info = new TextBox(700,300);
        $textBox_info->setColor(Color::getColor(Color::$BLACK));
        $textBox_info->setFont(Font::getFont(Font::$NOTOSERIF_REGULAR));
        $textBox_info->setSize(20);
        $textBox_info->setMargin(2);
        $textBox_info->setText($datampm->mc_name.'
'.$datampm->jml_lvl.' Lantai
'.'Alamat : '.$datampm->mpm_alamat);
        $imgx->setTextBox($textBox_info,($img->getWidth()-$textBox_info->getWidth())/2, 610, false);
        
        $textBox_ptjk = new TextBox(700,80);
        $textBox_ptjk->setColor(Color::getColor(Color::$BLACK));
        $textBox_ptjk->setFont(Font::getFont(Font::$NOTOSERIF_REGULAR));
        $textBox_ptjk->setSize(14);
        $textBox_ptjk->setMargin(2);
        $textBox_ptjk->setText('Petunjuk pemasangan : 
Cetak poster ini dengan ukuran A4
Tempatkan di lokasi yang mudah dilihat dan dijangkau');
        $imgx->setTextBox($textBox_ptjk,($img->getWidth()-$textBox_ptjk->getWidth())/2, 870, false);
        $imgx->save("./uploads/qrcode_mpm/qrmpm_".$mpm_id.".jpg",IMAGETYPE_PNG);
    
        $image = file_get_contents("./uploads/qrcode_mpm/qrmpm_".$mpm_id.".jpg");
        echo $image;
    }
    
    function pdfqrcode($mpm_id){
        $crypt = new mxencryption();
        $enc_mpmid = $crypt->encrypt(strval($mpm_id));
        
        try {
            $this->qrcode_bympmid($mpm_id);
			
            $datampm = $this->Printqrcode_model->printqrcode($mpm_id);
            
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
			$mpdf->curlAllowUnsafeSslRequests = true;
			$mpdf->showImageErrors = true;
            $url_bg = 'url("./uploads/bgqr2.jpg")';
			
            $url_bgx = './uploads/bgqr2.jpg';
            $qrcode = './uploads/qrcode_mpm/qrcode_'.$mpm_id.'.jpg';
    
            $namefilepdf = $file = preg_replace("/[^a-zA-Z0-9.]/", "_",$datampm->mpm_name);
            $mpdf->WriteHTML('<body style="background-image:url('.$url_bgx.'); background-image-resize:6">
                <br><br><br>
                <div align="center"><img src="'.$qrcode.'" width="50%"/><div>
                <div align="center"><font style="font-size:26px;font-family:Arial;">Scan untuk melihat informasi gedung ini</font><div>
                <br>
                <div style="margin-left:80px;margin-right:80px;">
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:26px;color:#235797;font-family:Arial;">
                        <b>'.$datampm->mpm_name.'</b></font>
                    </div>  
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:20px;font-family:Arial;">
                        <b>'.$datampm->mc_name.'</b></font>
                    </div>
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:20px;font-family:Arial;">
                        <b>'.$datampm->jml_lvl.' Lantai</b></font></div>
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:20px;font-family:Arial;">
                        Alamat : '.$datampm->mpm_alamat.'</font>
                    </div>
                    <div align="justify" style="margin-top:115px;">
                        <font style="font-size:16px;font-family:Arial;">
                            Petunjuk pemasangan : 
                            <br>Cetak poster ini dengan ukuran A4
                            <br>Tempatkan di lokasi yang mudah dilihat dan dijangkau
                        </font>
                    </div>   
                <div>
            </body>');
            //$mpdf->Output();
            ob_clean();
            $mpdf->Output('./uploads/qrcode_mpm/'.$enc_mpmid.'.pdf', 'F');
            $mpdf->Output($namefilepdf.'.pdf', 'D');
        } catch (\Mpdf\MpdfException $e) { 
            echo $e->getMessage();
        }
    }
    
    function pdfqrcode1($mpm_id){
        $crypt = new mxencryption();
        $enc_mpmid = $crypt->encrypt(strval($mpm_id));
        
        try {
            $this->qrcode_bympmid($mpm_id);
            
            $datampm = $this->Printqrcode_model->printqrcode($mpm_id);
            
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
            $mpdf->curlAllowUnsafeSslRequests = true;
            $mpdf->showImageErrors = true;
            $url_bg = 'url("./uploads/bgqr2.jpg")';
            
            $url_bgx = './uploads/bgqr2.jpg';
            $qrcode = './uploads/qrcode_mpm/qrcode_'.$enc_mpmid.'.jpg';
            
            $namefilepdf = $file = preg_replace("/[^a-zA-Z0-9.]/", "_",$datampm->mpm_name);
            $mpdf->WriteHTML('<body style="background-image:url('.$url_bgx.'); background-image-resize:6">
                <br><br><br>
                <div align="center"><img src="'.$qrcode.'" width="50%"/><div>
                <div align="center"><font style="font-size:26px;font-family:Arial;">Scan untuk melihat informasi gedung ini</font><div>
                <br>
                <div style="margin-left:80px;margin-right:80px;">
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:26px;color:#235797;font-family:Arial;">
                        <b>'.$datampm->mpm_name.'</b></font>
                    </div>
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:20px;font-family:Arial;">
                        <b>'.$datampm->mc_name.'</b></font>
                    </div>
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:20px;font-family:Arial;">
                        <b>'.$datampm->jml_lvl.' Lantai</b></font></div>
                    <div align="justify" style="margin-top:5px">
                        <font style="font-size:20px;font-family:Arial;">
                        Alamat : '.$datampm->mpm_alamat.'</font>
                    </div>
                    <div align="justify" style="margin-top:115px;">
                        <font style="font-size:16px;font-family:Arial;">
                            Petunjuk pemasangan :
                            <br>Cetak poster ini dengan ukuran A4
                            <br>Tempatkan di lokasi yang mudah dilihat dan dijangkau
                        </font>
                    </div>
                <div>
            </body>');
            ob_clean();
            $mpdf->Output('./uploads/qrcode_mpm/'.$enc_mpmid.'.pdf', 'F');
            echo json_encode(array("status" => 200, "message" => '"/uploads/qrcode_mpm/'.$enc_mpmid.'.pdf'));
        } catch (\Mpdf\MpdfException $e) {
            echo json_encode(array("status" => 500, "message" => $e->getMessage()));
        }
    }
}
