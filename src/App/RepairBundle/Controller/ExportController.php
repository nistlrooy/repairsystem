<?php

namespace App\RepairBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;

class ExportController extends Controller
{
    /**
     * @Route("/repairform2excel/{id}/{format}",name="repairform2excel",defaults={"format": "Excel5"},requirements={
     *     "format": "Excel5|Excel2007"
     * })
     * @Template()
     */
    public function repairForm2ExcelAction($id,$format = "Excel5")
    {
        $repairForm = $this->getDoctrine()->getManager()->getRepository('RepairBundle:RepairForm')->find($id);
        if (!$repairForm) {
            throw $this->createNotFoundException(
                '没有此维修工单: ' . $id
            );
        }


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Repair System")
            ->setLastModifiedBy("Repair System")
            ->setTitle("维修单")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("维修单")
            ->setCategory("Export");

        $phpExcelObject->getActiveSheet()->setTitle('维修单');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        if(!is_null($repairForm->getFormCondition()))
            $condition = $repairForm->getFormCondition()->getName();
        else
            $condition = '未知状态';

        if(!is_null($repairForm->getFaultInfo()->getTitle()))
            $title = $repairForm->getFaultInfo()->getTitle();
        else
            $title = '无标题';

        if(!is_null($repairForm->getRepairTask()->getCreateTime()))
            $createTime = $repairForm->getRepairTask()->getCreateTime();
        else
            $createTime = '未知时间';

        if(!is_null($repairForm->getLastUpdateTime()))
            $lastUpdateTime = $repairForm->getLastUpdateTime();
        else
            $lastUpdateTime = '未知时间';

        if(!is_null($repairForm->getFaultInfo()->getGroup()))
            $location = $repairForm->getFaultInfo()->getGroup()->getName();
        else
            $location = '未知地点';

        if(!is_null($repairForm->getFaultInfo()->getFaultType()))
            $type = $repairForm->getFaultInfo()->getFaultType()->getName();
        else
            $type = '未知类型';

        if(!is_null($repairForm->getFaultInfo()->getFaultPriority()))
            $priority =  $repairForm->getFaultInfo()->getFaultPriority()->getName();
        else
            $priority = '未知优先级';

        if(!is_null($repairForm->getCost()))
            $cost = '￥'.$repairForm->getCost();
        else
            $cost = '￥0.00';

        if(!is_null($repairForm->getFaultInfo()->getReporterDescription()))
            $reporterDescription = $repairForm->getFaultInfo()->getReporterDescription();
        else
            $reporterDescription = '暂无描述';

        if(!is_null($repairForm->getRepairTask()->getUser()->getName()))
            $reporter = $repairForm->getRepairTask()->getUser()->getName();
        else
            $reporter = '佚名';

        if(!is_null($repairForm->getRepairTask()->getUser()->getPhone()))
            $reporterPhone = $repairForm->getRepairTask()->getUser()->getPhone();
        else
            $reporterPhone = '未留电话';

        if(!is_null($repairForm->getFaultInfo()->getWorkerDescription()))
            $workerDescription = $repairForm->getFaultInfo()->getWorkerDescription();
        else
            $workerDescription = '暂无描述';

        if(!is_null($repairForm->getReceive()))
        {
            $workerPhone = $repairForm->getReceive()->getPhone();
            $worker = $repairForm->getReceive()->getName();
        }
        else
        {
            $workerPhone = '未留电话';
            $worker = '未知维修人';
        }
        if(!is_null($repairForm->getFaultInfo()->getFaultOrder()))
            $leaderOrder = $repairForm->getFaultInfo()->getFaultOrder()->getLeaderOrder();
        else
            $leaderOrder = '暂无批示';

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', '维修单')
            ->setCellValue('D1','维修单状态')
            ->setCellValue('E1', $condition)
            ->setCellValue('A2', '故障标题')
            ->setCellValue('B2', $title)
            ->setCellValue('A3', '报修时间')
            ->setCellValue('B3', $createTime)
            ->setCellValue('D3', '最后更新时间')
            ->setCellValue('E3', $lastUpdateTime)
            ->setCellValue('A4', '故障地点')
            ->setCellValue('B4', $location)
            ->setCellValue('D4', '故障类型')
            ->setCellValue('E4', $type)
            ->setCellValue('A5', '优先级')
            ->setCellValue('B5', $priority)
            ->setCellValue('D5', '维修金额')
            ->setCellValue('E5', $cost)
            ->setCellValue('A6', '报修人故障描述')
            ->setCellValue('B6', $reporterDescription)
            ->setCellValue('A7', '报修人姓名')
            ->setCellValue('B7', $reporter)
            ->setCellValue('D7', '报修人电话')
            ->setCellValue('A8', '维修人故障描述')
            ->setCellValue('B8', $workerDescription)
            ->setCellValue('A9', '维修人姓名')
            ->setCellValue('B9', $worker)
            ->setCellValue('D9', '维修人电话')
            ->setCellValue('A10', '领导批示')
            ->setCellValue('B10', $leaderOrder);

        $phpExcelObject->getActiveSheet()->setCellValueExplicit('E7', $reporterPhone, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcelObject->getActiveSheet()->setCellValueExplicit('E9', $workerPhone, PHPExcel_Cell_DataType::TYPE_STRING);

        $phpExcelObject->getActiveSheet()->mergeCells('E1:F1')
                                        ->mergeCells('B2:F2')
                                        ->mergeCells('B3:C3')
                                        ->mergeCells('E3:F3')
                                        ->mergeCells('B4:C4')
                                        ->mergeCells('E4:F4')
                                        ->mergeCells('B5:C5')
                                        ->mergeCells('E5:F5')
                                        ->mergeCells('B6:F6')
                                        ->mergeCells('B7:C7')
                                        ->mergeCells('E7:F7')
                                        ->mergeCells('B8:F8')
                                        ->mergeCells('B9:C9')
                                        ->mergeCells('E9:F9')
                                        ->mergeCells('B10:F10');

        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth('17');
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth('15');

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $phpExcelObject->getDefaultStyle()->applyFromArray($style);

        $phpExcelObject->getActiveSheet()->getStyle('A1:F10')->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);
        $numrows = $this->getRowcount($reporterDescription);
        $phpExcelObject->getActiveSheet()->getRowDimension(6)->setRowHeight($numrows * 12.75 + 2.25);
        $numrows = $this->getRowcount($title);
        $phpExcelObject->getActiveSheet()->getRowDimension(2)->setRowHeight($numrows * 12.75 + 2.25);
        $numrows =$this->getRowcount($workerDescription);
        $phpExcelObject->getActiveSheet()->getRowDimension(8)->setRowHeight($numrows * 12.75 + 2.25);
        $numrows =$this->getRowcount($leaderOrder);
        $phpExcelObject->getActiveSheet()->getRowDimension(10)->setRowHeight($numrows * 12.75 + 2.25);


        $BStyle = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                ),
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A1:F10')->applyFromArray($BStyle);

        if($format == 'Excel5')
        {
            // create the writer
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'Repair'.$repairForm->getId().'.xls'
            );
        }
        else
        {
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'Repair'.$repairForm->getId().'.xlsx'
            );
        }



        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;



    }

    public function getRowcount($text, $width=55) {
        $rc = 0;
        $line = explode("\n", $text);
        foreach($line as $source) {
            $rc += intval((strlen($source) / $width) +1);
        }
        return $rc;
    }


    /**
     * @Route("/numbytype2excel/{day}/{format}",name="numbytype2excel",defaults={"format": "Excel5"},requirements={
     *     "format": "Excel5|Excel2007","day": "\d+"
     * })
     * @Template()
     *
     */
    public function numByType2ExcelAction($day = 1000,$format="Excel5")
    {
        if(!$this->isGranted('ROLE_REPAIR_ADMIN'))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $type = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormNumberOfAllType($day);

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Repair System")
            ->setLastModifiedBy("Repair System")
            ->setTitle("故障类型统计")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("故障类型统计")
            ->setCategory("Export");

        $phpExcelObject->getActiveSheet()->setTitle('故障类型统计');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1','近'.$day. '天故障类型统计')
            ->setCellValue('A2','类型')
            ->setCellValue('B2','故障数')
            ->setCellValue('C2','比例');
        //总数
        $numberOfType = 0;
        foreach($type as $arr)
        {
            $numberOfType = $arr[0][1]+$numberOfType;
        }

        $index = 3;
        foreach($type as $key=>$value)
        {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$index,$key)
                ->setCellValue('B'.$index,$value[0][1])
                ->setCellValue('C'.$index,number_format(($value[0][1]/$numberOfType)*100).'%');
            $index++;
        }

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($index+1),'总计')
            ->setCellValue('B'.($index+1),'=SUM(B2:B'.($index-1).')');

        $phpExcelObject->getActiveSheet()->mergeCells('A1:C1');

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $phpExcelObject->getDefaultStyle()->applyFromArray($style);

        $BStyle = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                ),
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A1:C'.($index+1))->applyFromArray($BStyle);

        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth('17');


        if($format == 'Excel5')
        {
            // create the writer
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByType.xls'
            );
        }
        else
        {
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByType.xlsx'
            );
        }



        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }



    /**
     * @Route("/numbylocation2excel/{day}/{format}",name="numbylocation2excel",defaults={"format": "Excel5"},requirements={
     *     "format": "Excel5|Excel2007","day": "\d+"
     * })
     * @Template()
     *
     */
    public function numByLocation2ExcelAction($day = 100,$format="Excel5")
    {
        if(!$this->isGranted('ROLE_REPAIR_ADMIN'))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $location = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormNumberOfAllGroup($day);

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Repair System")
            ->setLastModifiedBy("Repair System")
            ->setTitle("故障所在地统计")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("故障所在地统计")
            ->setCategory("Export");

        $phpExcelObject->getActiveSheet()->setTitle('故障所在地统计');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1','近'.$day. '天故障所在地统计')
            ->setCellValue('A2','所在地')
            ->setCellValue('B2','故障数')
            ->setCellValue('C2','比例');
        //总数
        $numberOfLocation = 0;
        foreach($location as $arr)
        {
            $numberOfLocation = $arr+$numberOfLocation;
        }

        $index = 3;
        foreach($location as $key=>$value)
        {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$index,$key)
                ->setCellValue('B'.$index,$value)
                ->setCellValue('C'.$index,number_format(($value/$numberOfLocation)*100).'%');
            $index++;
        }

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($index+1),'总计')
            ->setCellValue('B'.($index+1),'=SUM(B2:B'.($index-1).')');

        $phpExcelObject->getActiveSheet()->mergeCells('A1:C1');

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $phpExcelObject->getDefaultStyle()->applyFromArray($style);

        $BStyle = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                ),
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A1:C'.($index+1))->applyFromArray($BStyle);

        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth('17');


        if($format == 'Excel5')
        {
            // create the writer
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByLocation.xls'
            );
        }
        else
        {
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByLocation.xlsx'
            );
        }



        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }


    /**
     * @Route("/numbystatus2excel/{month}/{format}",name="numbystatus2excel",defaults={"format": "Excel5"},requirements={
     *     "format": "Excel5|Excel2007","day": "\d+"
     * })
     * @Template()
     *
     */
    public function numByStatus2ExcelAction($month = 6,$format="Excel5")
    {
        if(!$this->isGranted('ROLE_REPAIR_ADMIN'))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $status = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormNumberOfAllStatus($month);


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Repair System")
            ->setLastModifiedBy("Repair System")
            ->setTitle("故障状况统计")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("故障状况统计")
            ->setCategory("Export");

        $phpExcelObject->getActiveSheet()->setTitle('故障状况统计');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1','近'.$month. '个月故障状况统计')
            ->setCellValue('A2','月份')
            ->setCellValue('B2','故障总数')
            ->setCellValue('C2','维修中')
            ->setCellValue('D2','已解决')
            ->setCellValue('E2','已解决比例');
        //总数


        $index = 3;
        foreach($status as $key=>$value)
        {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$index,$key)
                ->setCellValue('B'.$index,$value['all'])
                ->setCellValue('C'.$index,$value['repair'])
                ->setCellValue('D'.$index,$value['done']);
            if($value['all'] == 0)
            {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('E'.$index,'0%');
            }else
            {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('E'.$index,number_format(($value['done']/$value['all'])*100).'%');
            }

            $index++;
        }

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($index+1),'总计')
            ->setCellValue('B'.($index+1),'=SUM(B2:B'.($index-1).')')
            ->setCellValue('C'.($index+1),'=SUM(C2:C'.($index-1).')')
            ->setCellValue('D'.($index+1),'=SUM(D2:D'.($index-1).')');

        $phpExcelObject->getActiveSheet()->mergeCells('A1:E1');

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $phpExcelObject->getDefaultStyle()->applyFromArray($style);

        $BStyle = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                ),
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A1:E'.($index+1))->applyFromArray($BStyle);

        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth('15');
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth('15');

        if($format == 'Excel5')
        {
            // create the writer
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByStatus.xls'
            );
        }
        else
        {
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByStatus.xlsx'
            );
        }



        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }


    /**
     * @Route("/costbymonth2excel/{month}/{format}",name="costbymonth2excel",defaults={"format": "Excel5"},requirements={
     *     "format": "Excel5|Excel2007","day": "\d+"
     * })
     * @Template()
     *
     */
    public function costByMonth2ExcelAction($month = 6,$format="Excel5")
    {
        if(!$this->isGranted('ROLE_REPAIR_ADMIN'))
        {
            throw $this->createAccessDeniedException('Unauthorized access!');
        }

        $cost = $this->getDoctrine()->getRepository('RepairBundle:RepairForm')->getRepairFormCost($month);


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Repair System")
            ->setLastModifiedBy("Repair System")
            ->setTitle("维修金额统计")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("维修金额统计")
            ->setCategory("Export");

        $phpExcelObject->getActiveSheet()->setTitle('维修金额统计');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1','近'.$month. '个月维修金额统计')
            ->setCellValue('A2','月份')
            ->setCellValue('B2','维修金额')
            ;
        //总数


        $index = 3;
        foreach($cost as $key=>$value)
        {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$index,$key)
                ->setCellValue('B'.$index,$value)
                ;

            $index++;
        }

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($index+1),'总计')
            ->setCellValue('B'.($index+1),'=SUM(B2:B'.($index-1).')')
            ;

        $phpExcelObject->getActiveSheet()->mergeCells('A1:B1');

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $phpExcelObject->getDefaultStyle()->applyFromArray($style);

        $BStyle = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                ),
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A1:B'.($index+1))->applyFromArray($BStyle);

        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth('15');


        if($format == 'Excel5')
        {
            // create the writer
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByCost.xls'
            );
        }
        else
        {
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            // create the response
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            // adding headers
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'statisticsByCost.xlsx'
            );
        }



        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }


}
