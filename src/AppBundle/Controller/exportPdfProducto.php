<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 05/06/18
 * Time: 19:51
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Cliente;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;



class exportPdfProducto extends Controller
{

    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/pdfProducto/{id}", name="pdfProducto")
     * @Method({"GET", "POST"})
     */
    public function pdfProductoAction(Request $request,$id)
    {



        $array=array_map('intval', explode(',', $id));
        $array = implode("','",$array);

        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="SELECT producto.descripcion,SUM(producto_venta.cantidad) as cantidad FROM producto_venta INNER JOIN producto on producto.id=producto_venta.producto_id WHERE producto_venta.venta_id IN ('".$array."') GROUP BY producto.descripcion";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $lista=$stmt->fetchAll();



        $pdf = new \FPDF();
        $pdf->AddPage();



        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 15);
        $pdf->MultiCell(100, 5, utf8_decode('ITEMS'), 1, '');
        $pdf->SetXY(110, 15);
        $pdf->MultiCell(30, 5, utf8_decode('Unidades'), 1, 'C');

        $posY = 20;

        //Original
        #$this->createBlackLineTable($posY);



        $cant = 0;
        foreach ($lista as $item) {
            if ($cant > 20) {
                $this->newPage($pdf);
                $cant = 0;
                $posY = 20;
            }

            $this->createLineTable($item["descripcion"], $item["cantidad"], $posY,$pdf);
            $posY += 10;
            $cant++;
        }
        $posY += 5;
        $cant++;

        return new \Symfony\Component\HttpFoundation\Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }
    function Footer()
    {

        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 10, 'Hoja ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function createLineTable($nombre, $cantidad, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 8);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(100, 10, utf8_decode($nombre), 1, 'L');
        $pdf->SetXY(110, $posY);
        $pdf->MultiCell(30, 10, utf8_decode($cantidad), 1, 'L');


    }
    function newPage($pdf)
    {
        $pdf->AddPage();

        //$this->Image($_SERVER['DOCUMENT_ROOT'].'/includes/images/iso/9105078191_139737_T.jpg', 15, 100, 180, 90);

        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 15);
        $pdf->MultiCell(100, 5, utf8_decode('ITEMS'), 1, '');
        $pdf->SetXY(110, 15);
        $pdf->MultiCell(30, 5, utf8_decode('Unidades'), 1, 'C');


    }

}