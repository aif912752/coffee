<?php
// เรียกใช้ autoload.php ของ Composer หากคุณใช้ Composer

// เรียกใช้ FPDF หรือ TCPDF หรือไลบรารีอื่น ๆ ที่คุณใช้สร้าง PDF
require_once 'fpdf186/fpdf.php';

class MyPDF extends FPDF {
    // เพิ่มเมธอดสำหรับสร้างเอกสาร PDF ตามความต้องการ
    function createInvoice() {
        // เริ่มเอกสาร PDF
        $this->AddPage();
        
        // กำหนดฟอนต์และขนาดตัวอักษร
        $this->SetFont('Arial', 'B', 16);

        // เพิ่มหัวเรื่องหรือเนื้อหาในเอกสาร PDF ตามที่ต้องการ
        $this->Cell(40, 10, 'Hello World!'); // ตัวอย่างการเพิ่มข้อความ
    }
}

// สร้างอ็อบเจ็กต์ PDF
$pdf = new MyPDF();

// เรียกใช้เมธอด createInvoice เพื่อสร้างเอกสาร PDF
$pdf->createInvoice();

// ส่งออกเอกสาร PDF ไปยังไฟล์
$pdfFile = 'bill.pdf';
$pdf->Output($pdfFile, 'F');

// เรียกใช้ HTML iframe เพื่อแสดงเอกสาร PDF บนหน้าเว็บ
?>
<iframe src="<?php echo $pdfFile; ?>" width="500" height="600"></iframe>
