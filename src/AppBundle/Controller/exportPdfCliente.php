<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 05/06/18
 * Time: 13:16
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Cliente;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class exportPdfCliente extends Controller
{
    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/pdfCliente/{id}", name="pdfCliente")
     * @Method({"GET", "POST"})
     */
    public function pdfClienteAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $idsArray = array_map('intval', explode(',', $id));

        $pdf = new \FPDF();
        $pdf->AddPage();


        //$this->Image($_SERVER['DOCUMENT_ROOT'].'/includes/images/iso/9105078191_139737_T.jpg', 15, 100, 180, 90);

        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 10);
        $pdf->MultiCell(60, 10, utf8_decode('Clientes'), 1, '');

        $pdf->SetXY(70, 10);
        $pdf->MultiCell(60, 10, utf8_decode('$'), 1, '');

        $pdf->SetXY(130, 10);
        $pdf->MultiCell(60, 10, utf8_decode('Observaciones'), 1, '');

        $posY = 20;

        //Original
        #$this->createBlackLineTable($posY);



        $cant = 0;
        foreach ($idsArray as $item) {
            if ($cant > 20) {
                $cant = 0;
                $posY = 20;
                $this->newPage($pdf);

            }
            $cliente= $em->getRepository('AppBundle:Venta')->find($item);

            $this->createLineTable($cliente->getCliente()->getNombre(), $cliente->getCliente()->getNombre(), $posY,$pdf);
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

        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(60, 10, utf8_decode($nombre), 1, 'L');

        $pdf->SetXY(70,$posY);
        $pdf->MultiCell(60, 10, '', 1, '');

        $pdf->SetXY(130,$posY);
        $pdf->MultiCell(60, 10, '', 1, '');


    }
    function newPage($pdf)
    {
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 10);
        $pdf->MultiCell(60, 10, utf8_decode('Clientes'), 1, '');

        $pdf->SetXY(70, 10);
        $pdf->MultiCell(60, 10, utf8_decode('$'), 1, '');

        $pdf->SetXY(130, 10);
        $pdf->MultiCell(60, 10, utf8_decode('Observaciones'), 1, '');

    }
}