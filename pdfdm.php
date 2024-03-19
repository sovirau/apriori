<?php
/* 
author : aldi arema
*/  
 // mesetting direktori FPDF 
// require('fpdf17/fpdf.php');
require('fpdf.php');
include_once '../config.php';

$id_proses = $_GET['id'];

$datamining = mysqli_query($koneksi,"SELECT * from confidence 
                            inner join proses
                            on confidence.id_proses = proses.id_proses
                            inner join itemset2
                            on itemset2.id_proses = proses.id_proses
                            and confidence.item1 = itemset2.item1
                            and confidence.item2 = itemset2.item2
                            where confidence.nilai_confidence >= proses.min_confidence and korelasi = 'pos' and confidence.id_proses = ".$id_proses);
  
//Meninitial objek FPDF 
 $pdf=new FPDF('P','mm','A4'); 
  
 //Menambah Halaman 
 $pdf->AddPage(); 
  
 //Menentukan jenis huruf 
 $pdf->SetFont('Arial', 'B',11); 
  
 //mengubah mengubah warna font menjadi Merah 
 //$pdf->SetTextColor(194,8,8);  
  $pdf->SetTextColor(0,0,0);  
 // Mencetak tulisan  
 // Angka 0  menunjukan lebar space tulisan  dari kiri kekanan,jika 0 berarti lebarnya maksimum sesuai dengan lebar kertas 
 // Angka dua menunjukan tinggi tulisan  
 // Angka 0 parameter ke-4 menunjukan tanpa border 
 // Angka 0 parameter ke-5 menunjukan aris selanjutnya yang pada kasus ini kita gantikan dengan Ln() 
 $pdf->Image('bsm.jpg',15,4,28,28);
 $pdf->SetFont('Arial', 'B',18);
 $pdf->Cell(70,2, '',0,0);
 $pdf->Cell(80,2, 'Laporan Hasil Analisa Data',0,0, 'C');
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
 $pdf->SetFont('Arial', 'B',14);
 $pdf->Cell(83,2, '',0,0);
 $pdf->Cell(0,2, 'Bandung Super Model',0,0);
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
 $pdf->SetFont('Arial', '',9);
 $pdf->Cell(43,2, '',0,0);
 $pdf->Cell(0,2, 'Jl. Raya Sengkaling No.190, Sengkaling, Mulyoagung, Kec. Dau, Malang, Jawa Timur 65151',0,0);
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
  //$pdf->Line(20, 15, 190, 15);
 $pdf->SetLineWidth(1);
 $pdf->Line(10,33,200,33);
 $pdf->SetLineWidth(0);
 $pdf->Line(10,34,200,34);
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
 $pdf->Ln();
 $pdf->SetFont('Arial', 'B',10);
 $pdf-> Cell(10,6,'No',1,0, 'C');
 	$pdf-> Cell(135,6,'Rule',1,0, 'C');
 	$pdf-> Cell(25,6,'Confidence',1,0, 'C');
 	$pdf-> Cell(20,6,'Lift Ratio',1,1, 'C');
         $pdf->SetFont('Arial', '',10);
         $no=1;
         while($data = mysqli_fetch_array($datamining)){
            $itemset = "Jika membeli '".$data['item1']."' maka akan membeli '".$data['item2']."'";
            $cellWidth=135; //lebar sel
            $cellHeight=5;
            if($pdf->GetStringWidth($itemset) < $cellWidth){
                //jika tidak, maka tidak melakukan apa-apa
                $line=1;
            }else{
                $textLength=strlen($itemset);	//total panjang teks
                $errMargin=20;		//margin kesalahan lebar sel, untuk jaga-jaga
                $startChar=0;		//posisi awal karakter untuk setiap baris
                $maxChar=0;			//karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray=array();	//untuk menampung data untuk setiap baris
                $tmpString="";

                while($startChar < $textLength){ //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while( 
                    $pdf->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) &&
                    ($startChar+$maxChar) < $textLength ) {
                        $maxChar++;
                        $tmpString=substr($itemset,$startChar,$maxChar);
                    }
                    $startChar=$startChar+$maxChar;
                        //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                        array_push($textArray,$tmpString);
                        //reset variabel penampung
                        $maxChar=0;
                        $tmpString='';
                        
                    }
                    $line=count($textArray);
                }


            $pdf-> Cell(10,($line * $cellHeight),$no,1,0, 'C');
            $xPos=$pdf->GetX();
	        $yPos=$pdf->GetY();
            $pdf-> MultiCell($cellWidth,$cellHeight,$itemset,1);
            $pdf->SetXY($xPos + $cellWidth , $yPos);
            $pdf-> Cell(25,($line * $cellHeight),$data['nilai_confidence'],1,0, 'C');
            $pdf-> Cell(20,($line * $cellHeight),$data['nilai_lift'],1,1, 'C');
                    $no++; }
            
 	$pdf->Ln();
 	$pdf->Ln();
date_default_timezone_set('Asia/Jakarta');
$now = date("l, d F Y - H:i:s");
$pdf-> Cell(10,6,'Dicetak pada: '.$now.' ');
 $pdf->output('hasilolahdata.pdf', 'I');
?> 