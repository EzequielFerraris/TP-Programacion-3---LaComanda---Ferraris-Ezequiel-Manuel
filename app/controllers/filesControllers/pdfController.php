<?php
use Psr\Http\Message\UploadedFileInterface;
use Slim\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

include_once "models/productos/producto.php";
include_once "models/trabajadores/Trabajador.php";
include_once "models/pedidos/pedido.php";
include_once "models/auditLog/auditLog.php";
include_once "models/pdf/MYPDF.php";

class PdfController
{
    public static string $directoryDownload = "download/pdf"; 
    
    public function descargarPDF($request, $response, $args)
    {
        
        $fileName = "informe.pdf";

        $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false, true);

        // set document information
        $pdf->SetCreator('La Comanda');
        $pdf->SetAuthor('La Comanda');
        $pdf->SetTitle('Informe productos');
        $pdf->SetSubject('Listado de productos');
        $pdf->SetKeywords('productos, PDF, La Comanda, lista');

        // set default header data
        $pdf->SetHeaderData('media/logo.png', PDF_HEADER_LOGO_WIDTH, "Lista de Productos", "La Comanda.inc");

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/esp.php')) {
            require_once(dirname(__FILE__).'/lang/esp.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->addPage();

         // Contenido
         //Título
        $pdf->setXY(10, 40);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(180,10,'Acciones en la aplicación',0,1, "C");
        $pdf->Ln(1);
        //Subtítulo
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(180,10,'Tabla de registros:',0,1, "L");
        $pdf->Ln(5);

        self::crearTabla("logs", $pdf);

        $content = $pdf->output('doc.pdf', 'S');

        $response->getBody()->write($content);

        $response = $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $fileName));

        return $response;   
        
    }

    public static function crearTabla(string $objetoCSV, $pdf)
    {
        
        switch($objetoCSV)
        {
            case "producto":
                $listaObjetos = Producto::obtenerTodosArray();
            break;
            case "pedido":
                $listaObjetos = Pedido::obtenerTodosArray();
            break;
            case "trabajador":
                $listaObjetos = Trabajador::obtenerTodosArray();
            break;
            case "logs":
                $listaObjetos = AuditLog::obtenerTodas();
                $pdf->Cell(44,5,"Fecha",1);
                $pdf->Cell(17,5,"Usuario",1);
                $pdf->Cell(50,5,"Mail",1);
                $pdf->Cell(20,5,"Puesto",1);
                $pdf->Cell(45,5,"Acción",1); 
                $pdf->Ln();
                foreach($listaObjetos as $obj)
                {
                    $pdf->Cell(44,5, "" . $obj->fecha,1);
                    $pdf->Cell(17,5, "" . $obj->id_usuario,1);
                    $pdf->Cell(50,5, "" . $obj->mail,1);
                    $pdf->Cell(20,5, "" . $obj->puesto,1);
                    $pdf->Cell(45,5, "" . $obj->accion,1);
                    $pdf->Ln();
                }
            break;
        }    
        
    }

   
}

?>