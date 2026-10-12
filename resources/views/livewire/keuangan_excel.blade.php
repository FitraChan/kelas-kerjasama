<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


  $objPHPExcel = new Spreadsheet();

  $objPHPExcel->setActiveSheetIndex(0);
       $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
         $objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object


         for($a=1;$a<=2;$a++){
           $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$a.':F'.$a);
           $objPHPExcel->getActiveSheet()->getRowDimension($a)->setRowHeight(16);

           $objget->getStyle('A'.$a.':F'.$a)->getAlignment()->setHorizontal('center');

         }

         $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11)->setBold(true);
         $objset->setCellValue("A1", "TRANSAKSI MAHASISWA");

         $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11)->setBold(true);
         $objset->setCellValue("A2", "STIKOM BALI");

    $no_head = 3;
              $cols = array("A","B","C","D","E","F");
              $val = array("No","NIM","NAMA","TGL TRANSAKSI","VIA","STATUS");
         for ($a=0;$a<6; $a++) {

               $objPHPExcel->getActiveSheet()->getStyle('A'.$no_head.':F'.$no_head)->getFont()->setSize(10)->setBold(true);
                $objget->getStyle('A'.$no_head.':F'.$no_head)->getAlignment()->setHorizontal('center');
               $objPHPExcel->getActiveSheet()->setCellValue($cols[$a].$no_head, $val[$a]);
                $objPHPExcel->getActiveSheet()->getRowDimension($no_head)->setRowHeight(20);

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // NAMA
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12); // ALAMAT
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30); // Kontak
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(23);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

         }


       $cell = 4;

       $no = 1;

       foreach($keu as $row){

             $objget->getStyle('A'.$cell.':B'.$cell)->getAlignment()->setHorizontal('center');

         	 $date = date('d-m-Y', strtotime($row->tgl_trans));
           $objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, $no);
           $objPHPExcel->getActiveSheet()->setCellValue('B'.$cell, $row->str_nim);
           $objPHPExcel->getActiveSheet()->setCellValue('C'.$cell, $row->buyer_name);
           $objPHPExcel->getActiveSheet()->setCellValue('D'.$cell, $date);
           $objPHPExcel->getActiveSheet()->setCellValue('E'.$cell, $row->nama_bank);
           $objPHPExcel->getActiveSheet()->setCellValue('F'.$cell, $row->ket_status);
           $cell++;
           $no++;
       }
       $writer = new Xlsx($objPHPExcel);
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="Jogjatech-export.xlsx"');
       $writer->save('php://output');
