<?php
set_time_limit(600);
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
include('header.php');
require ('vendor\phpoffice\phpspreadsheet\samples\Header.php');
$author = $_SESSION['user'];

//-------------------
$helper->log('Create new Spreadsheet object');
$spreadsheet = new Spreadsheet();

// Set document properties
$helper->log('Set document properties');
$spreadsheet->getProperties()
    ->setCreator("$author")
    ->setLastModifiedBy("$author")
    ->setTitle('Реєстр ДМДК - всі суб\'єкти')
    ->setSubject('Реєстр ДМДК')
    ->setDescription('Всі суб\'єкти')
    ->setKeywords('Всі суб\'єкти')
    ->setCategory('Всі суб\'єкти');

// Set default font
$helper->log('Set default font');
$spreadsheet->getDefaultStyle()
    ->getFont()
    ->setName('Arial')
    ->setSize(10);
	
//Set bold style
$boldStyle = [
            'font' => [
                'bold' => true,
            ]
        ];

$sheet = $spreadsheet->getActiveSheet();
	
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(8);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(50);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(10);

$producer_color = 'c3ffbf';
$producerWithImen_color = '43b83a';
$checkingSubject_color = 'e4ff00';
$checkingAdress_color = 'ffa800';


$sheet->getStyle('A1:Z1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$producer_color");	
$sheet->setCellValue('C1', 'Виробник без іменника');
$sheet->getStyle('D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$producerWithImen_color");	
$sheet->setCellValue('D1', 'З іменником');
$sheet->getStyle('E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$checkingSubject_color");	
$sheet->setCellValue('E1', 'Перевірити дані суб\'єкта');
$sheet->getStyle('F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$checkingAdress_color");	
$sheet->setCellValue('F1', 'Перевірити адресу');

$sheet->getStyle('A3:Z3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3:Z3')->applyFromArray($boldStyle);

$sheet->setCellValue('A3', '# в реєстрі');
$sheet->setCellValue('B3', 'ОПФ');
$sheet->setCellValue('C3', 'Назва');
$sheet->setCellValue('D3', 'ЄДРПОУ');
$sheet->setCellValue('E3', 'Юридична адреса');
$sheet->setCellValue('F3', 'Дата внесення');
$sheet->setCellValue('G3', 'Іменник');

$writer = new Xlsx($spreadsheet);

$sql="SELECT * FROM person where state='1' || state='3' ORDER BY date_start DESC limit 250";
$result=mysqli_query($link, $sql);

$x=5;					
while ($row = mysqli_fetch_assoc($result)) {
	$state = $row['state'];
	if ($state == '3'){
	$cells=('A'.$x.':Z'.$x);
	$sheet->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$checkingSubject_color");	
	}
	
	$person_id = $row['person_id'];

	$sql_ifProducer = "SELECT * FROM adress WHERE person_id = $person_id
						AND (	worktype LIKE '%,2,%' 
							OR worktype LIKE '%,2' 
							OR worktype LIKE '2,%' 
							OR worktype = '2')";
	$result_ifProducer=mysqli_query($link, $sql_ifProducer);
	$fetch_ifProducer = mysqli_fetch_array($result_ifProducer);
	if ($fetch_ifProducer['current'] == '1'){
		$cells=('A'.$x.':Z'.$x);
		$sheet->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$producer_color");	
	}
	
	
	$regno = $row['regno'];
	$sheet->getStyle('A'.$x)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('A'.$x, $regno);
	
	$opf = $row['opf_id'];
	$sheet->getStyle('B'.$x)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$opf = show_opf_alias($opf);
	$sheet->setCellValue('B'.$x, $opf);
	
	$name = $row['name'];
	$sheet->setCellValue('C'.$x, $name);
	
	$tax_code = $row['tax_code'];
	$sheet->getStyle('D'.$x)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('D'.$x, "$tax_code");
	
	$sql_main_adress = "SELECT * FROM adress WHERE person_id = $person_id AND is_main = 1 AND (current = 1 || current = 3) order by start_date desc";
	$result_main_adress = mysqli_query($link, $sql_main_adress);
	$main_adress = mysqli_fetch_array($result_main_adress);
	$state = $main_adress['current'];
	if ($state == '3'){
		$cells=('E'.$x);
		$sheet->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$checkingAdress_color");	
	}
	
	$mainadr='';
		if ($main_adress['postindex']) {
			$mainadr.=$main_adress['postindex'].", ";
		}
		
		$region=$main_adress['region'];
		if (($region)&&($region!=='1')) {
			$region=show_region_name($region);
			$mainadr.=$region.", ";
		}
		if ($main_adress['area']) {
			$mainadr.=$main_adress['area'].", ";
		}
		if ($main_adress['city']) {
			$mainadr.=$main_adress['city'].", ";
		}
	$mainadr .= $main_adress['adress'];
	$sheet->getStyle('E'.$x)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
	$sheet->setCellValue('E'.$x, $mainadr);
	
	$date_start = $row['date_start'];
	$date_start = date('d.m.Y', strtotime($date_start));
	$sheet->getStyle('F'.$x)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('F'.$x, "$date_start");
	
	$sql_imen = "SELECT cipher FROM imen WHERE person_id = $person_id and is_valid = '1' order by start_date desc limit 1";
	$result_imen = mysqli_query($link, $sql_imen);
	$imen_ar = mysqli_fetch_array($result_imen);
	$imen = $imen_ar['cipher'];
	if ($imen){
		$cells=('A'.$x.':Z'.$x);
		$sheet->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("$producerWithImen_color");	
		$sheet->getStyle('G'.$x)->applyFromArray($boldStyle);
	}
	$sheet->getStyle('G'.$x)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('G'.$x, $imen);

	
	$x++;
}

$time = date("YmdHi");
$writer->save("reports/dmdk-$time.xlsx");
echo '<script>window.location.href="all_subjects.php";</script>';
