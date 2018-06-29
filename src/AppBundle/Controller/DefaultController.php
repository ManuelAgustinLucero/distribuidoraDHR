<?php

namespace AppBundle\Controller;

use AppBundle\Entity\clienteProducto;
use AppBundle\Entity\productoVenta;
use AppBundle\Entity\Venta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Cliente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Exception\ValidatorException;


use Symfony\Component\HttpFoundation\Response;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        // replace this example code with whatever you need
        if ($this->getUser()){
            return $this->render('default/index.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            ]);
        }else{
            return $this->redirectToRoute('fos_user_security_login');

        }

    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/listaProductos/{id}", name="listaProductos")
     * @Method({"GET", "POST"})
     */
    public function listProductAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="select p.id,p2.id as clienteProducto,p2.producto_id as idProducto,p2.cliente_id as idCliente, p.descripcion, p.precio, p2.precio, COALESCE((p2.precio), p.precio, p2.precio) as precioVerdadero from producto as p left join cliente_producto as p2 on p.id = p2.producto_id and p2.cliente_id =$id";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $lista=$stmt->fetchAll();

        return $this->render('cliente/listProduct.html.twig', array(
            'listas' => $lista,
            'idClienteEntrante'=>$id

        ));

    }


    /**
     * Creates a new cliente entity.
     *
     * @Route("/change/listaProductos", name="changeProdocuto")
     * @Method({"GET", "POST"})
     */
    public function changeProdocutoAction(Request $request)
    {
        $idClienteProducto = $request->request->get('id');

        $precio = $request->request->get('precio');


        $em = $this->getDoctrine()->getManager();

        $clienteProducto = $em->getRepository('AppBundle:clienteProducto')->find($idClienteProducto);
        $clienteProducto->setPrecio($precio);
        $em->persist($clienteProducto);
        $em->flush();



        return new JsonResponse("Precio Actualizado");





    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/crear/listaProductos", name="crearProdocuto")
     * @Method({"GET", "POST"})
     */
    public function crearProdocutoAction(Request $request)
    {
        $idCliente = $request->request->get('idCliente');
        $idProducto = $request->request->get('idProducto');
        $precio = $request->request->get('precio');

        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('AppBundle:Cliente')->find($idCliente);
        $producto = $em->getRepository('AppBundle:Producto')->find($idProducto);

        $clienteProducto = new clienteProducto();
        $clienteProducto->setCliente($cliente);
        $clienteProducto->setProducto($producto);
        $clienteProducto->setPrecio($precio);
        $em->persist($clienteProducto);
        $em->flush();

        return new JsonResponse(" Precio Actualizado");





    }

    /**
     * Creates a new cliente entity.
     *
     * @Route("/get/listaProductosClienteJson", name="listaProductosClienteJson")
     * @Method({"GET", "POST"})
     */
    public function listaProductosClienteJsonAction(Request $request)
    {
        $id= $request->request->get('idCliente');


        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="select p.id,p2.id as clienteProducto,p2.producto_id as idProducto,p2.cliente_id as idCliente, p.descripcion, p.precio, p2.precio, COALESCE((p2.precio), p.precio, p2.precio) as precioVerdadero from producto as p left join cliente_producto as p2 on p.id = p2.producto_id and p2.cliente_id =$id";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $lista=$stmt->fetchAll();

        return new JsonResponse($lista);


    }

    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/altaVenta", name="altaVenta")
     * @Method({"GET", "POST"})
     */
    public function altaVentaAction(Request $request)
    {
        $idCliente= $request->request->get('idCliente');
        $productoVenta= $request->request->get('productoVenta');
        $total= $request->request->get('total');
        $em = $this->getDoctrine()->getManager();

        $cliente = $em->getRepository('AppBundle:Cliente')->find($idCliente);

        $venta =new Venta();
        $venta->setCliente($cliente);
        $venta->setTotal($total);
        $venta->setFecha(new \DateTime('now'));
        $venta->setTotalPagado(0);
        $venta->setActualizada(false);
        $venta->setSaldada(false);
        $em->persist($venta);
        $em->flush();

        foreach ($productoVenta as $prodVent){

            $producto = $em->getRepository('AppBundle:Producto')->find($prodVent["idProducto"]);
            $venta = $em->getRepository('AppBundle:Venta')->find($venta->getId());
            $productoVentaNew= new productoVenta();
            $productoVentaNew->setProducto($producto);
            $productoVentaNew->setVenta($venta);
            $productoVentaNew->setPrecio($prodVent["precioUnidad"]);
            $productoVentaNew->setCantidad( $prodVent["cantidad"]);
            $em->persist($productoVentaNew);
            $em->flush();
        }
        return new JsonResponse($venta->getId());


    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/fechaVenta/{fecha}", name="fechaVenta")
     * @Method({"GET", "POST"})
     */
    public function fechaVentaAction(Request $request,$fecha)
    {
//        $fecha= $request->request->get('fecha');
        $f=new \DateTime($fecha);
        $f1=$f->format('Y-m-d 00:00:00');
        $f2=$f->format('Y-m-d 23:59:00');
        $em = $this->getDoctrine()->getManager();
//
//        $db = $em->getConnection();
//        $query ="select * from venta  where venta.fecha between '$f1' and '$f2'";
//
//        $stmt = $db->prepare($query);
//        $params = array();
//        $stmt->execute($params);
//        $ventas=$stmt->fetchAll();

        $ventas = $this->getDoctrine()
            ->getManager()
            ->createQuery("SELECT e FROM AppBundle:Venta e WHERE e.fecha BETWEEN '$f1' AND '$f2'")
            ->getResult();

        return $this->render('venta/index.html.twig', array(
            'ventas' => $ventas,

        ));

    }

    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/pdfVenta/{id}", name="pdfVenta")
     * @Method({"GET", "POST"})
     */
    public function pdfVentaAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $venta= $em->getRepository('AppBundle:Venta')->find($id);
        $productoVenta= $em->getRepository('AppBundle:productoVenta')->findBy( ['venta' =>$id]);

        $pdf = new \FPDF();

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);


        $pdf->SetFont('Arial', '', 12);

        $pdf->MultiCell(190, 16, '', 1, 'C');

        $pdf->Image( 'assets/img/logo.jpg', 15, 11, 30);


        $pdf->MultiCell(0, 30, '', 1, 'C');
        $pdf->SetXY(15, 30);
        $pdf->MultiCell(0, 5, 'Nombre: '. utf8_decode( $venta->getCliente()->getNombre()), 0, '');

        $pdf->SetXY(15, 40);
        $pdf->MultiCell(0, 5, 'Direccion: ' . utf8_decode($venta->getCliente()->getDireccion()), 0, '');

        $pdf->SetXY(15, 50);
        $pdf->MultiCell(0, 5, 'Telefono: '. utf8_decode($venta->getCliente()->getTelefono()), 0, '');

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(145, 30);
        $pdf->MultiCell(0, 5, 'Fecha emision: ' .  $venta->getFecha()->format('d/m/Y'), 0, '');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(145, 40);
        $pdf->MultiCell(0, 5, 'Localidad: ' . utf8_decode($venta->getCliente()->getZona()), 0, '');
        $pdf->Ln(10);



        $pdf->SetFont('Arial', 'B', 9);

        $pdf->SetXY(10, 56);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
        $pdf->SetXY(30, 63);
        $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
        $pdf->SetXY(140, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
        $pdf->SetXY(160, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
        $pdf->SetXY(180, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');




        $posY = 70;

//        Original
        $this->createBlackLineTable($pdf,$posY);

//        $datos = ["manzana","pera"];

        $cant = 0;
        foreach ( $productoVenta as $key => $producto) {
            if ($cant > 36) {
                $this->newPage($pdf);
                $cant = 0;
                $posY = 65;
            }
            $this->createLineTable($key, $producto, $posY,$pdf);
            $posY += 5;
            $cant++;
        }
//        $this->createLineDeuda("", $venta->getTotalPagado(), $posY,$pdf);
        $this->createLineTotal($venta->getTotal(), $posY,$pdf);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(10, $posY+10);
        $pdf->MultiCell(20, 5, utf8_decode('Deuda:'), 2, 'L');
        $pdf->SetXY(30, $posY+10);
        $pdf->MultiCell(20, 5, utf8_decode('$'), 1, 'L');
//        $posY += 5;
        $cant++;

//
//        $this->createRetirementTable($posY,$pdf);

        //NUEVA PARTE
//        $pdf->Line(0, 130, 300, 130); // 20mm from each edge
        if (count($productoVenta) > 8) {

//            $this->newPage($pdf);
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',16);


            $pdf->SetFont('Arial', '', 12);

            $pdf->MultiCell(190, 16, '', 1, 'C');

            $pdf->Image( 'assets/img/logo.jpg', 15, 11, 30);


            $pdf->MultiCell(0, 30, '', 1, 'C');
            $pdf->SetXY(15, 30);
            $pdf->MultiCell(0, 5, 'Nombre: '. utf8_decode( $venta->getCliente()->getNombre()), 0, '');

            $pdf->SetXY(15, 40);
            $pdf->MultiCell(0, 5, 'Direccion: ' . utf8_decode($venta->getCliente()->getDireccion()), 0, '');

            $pdf->SetXY(15, 50);
            $pdf->MultiCell(0, 5, 'Telefono: '. utf8_decode($venta->getCliente()->getTelefono()), 0, '');

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(145, 30);
            $pdf->MultiCell(0, 5, 'Fecha emision: ' .  $venta->getFecha()->format('d/m/Y'), 0, '');

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(145, 40);
            $pdf->MultiCell(0, 5, 'Localidad: ' . utf8_decode($venta->getCliente()->getZona()), 0, '');
            $pdf->Ln(10);
            //$this->Image($_SERVER['DOCUMENT_ROOT'].'/includes/images/iso/9105078191_139737_T.jpg', 15, 100, 180, 90);

            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(10, 56);
            $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
            $pdf->SetXY(10, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
            $pdf->SetXY(30, 63);
            $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
            $pdf->SetXY(140, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
            $pdf->SetXY(160, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
            $pdf->SetXY(180, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');








            $posY = 70;

            //Original
            $this->createBlackLineTable( $pdf,$posY);

            $datos = ["manzana","pera"];

            $cant = 0;
            foreach ( $productoVenta as $key => $producto) {
                if ($cant > 36) {
                    $this->newPage($pdf);
                    $cant = 0;
                    $posY = 65;
                }
                $this->createLineTable($key, $producto, $posY,$pdf);

                $posY += 5;
                $cant++;
            }

            $this->createLineTotal($venta->getTotal(), $posY,$pdf);

//
//            $this->createRetirementTable($posY,$pdf);
        } else {
            //Copia

            $posYCopia = 150;
            $pdf->SetXY(10, 10 + $posYCopia);
            $pdf->SetFont('Arial', '', 12);




            $pdf->MultiCell(190, 16, ' ', 1, 'C');
            $pdf->Image( '/home/manuel/trabajos/facturas/web/assets/img/logo.jpg', 15, 161, 30);

            $pdf->MultiCell(0, 30, '', 1, 'C');
            $pdf->SetXY(15, 30 + $posYCopia);
            $pdf->MultiCell(0, 5, 'Nombre: '. utf8_decode($venta->getCliente()->getNombre()), 0, '');

            $pdf->SetXY(15, 40+ $posYCopia);
            $pdf->MultiCell(0, 5, 'Direccion: ' . utf8_decode($venta->getCliente()->getDireccion()), 0, '');

            $pdf->SetXY(15, 50+ $posYCopia);
            $pdf->MultiCell(0, 5, 'Telefono: '. utf8_decode($venta->getCliente()->getTelefono()), 0, '');

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(145, 30+ $posYCopia);
            $pdf->MultiCell(0, 5, 'Fecha emision: ' . $venta->getFecha()->format('d/m/Y'), 0, '');

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(145, 40+ $posYCopia);
            $pdf->MultiCell(0, 5, 'Localidad: ' . utf8_decode($venta->getCliente()->getZona()), 0, '');

            $pdf->Ln(10);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(10, 56 + $posYCopia);
            $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
            $pdf->SetXY(10, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
            $pdf->SetXY(30, 63 + $posYCopia);
            $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
            $pdf->SetXY(140, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
            $pdf->SetXY(160, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
            $pdf->SetXY(180, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');


            $posYCopia = 70 + $posYCopia;
            $this->createBlackLineTable($pdf,$posY);

            $datos = ["manzana","pera"];

            $cant = 0;
            foreach ($productoVenta as $key => $producto) {
                $this->createLineTable($key, $producto, $posYCopia,$pdf);
                $posYCopia += 5;
                $cant++;
            }

//            $this->createLineDeuda("", $venta->getTotalPagado(), $posYCopia,$pdf);
            $this->createLineTotal($venta->getTotal(), $posYCopia,$pdf);
            $posYCopia += 5;
            $cant++;

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(10, $posYCopia+5);
            $pdf->MultiCell(20, 5, utf8_decode('Deuda:'), 2, 'L');
            $pdf->SetXY(30, $posYCopia+5);
            $pdf->MultiCell(20, 5, utf8_decode('$'), 1, 'L');


//            $this->createRetirementTable($posYCopia,$pdf);
        }
        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));


    }
    function createLineTotal($total, $posY,$pdf)
    {
        $pdf->SetFont('Arial', '',14);
        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5.2, "", 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5.2, "", 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5.2, "", 1, 'C');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5.2, "Total:", 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5.2, "$" . utf8_decode($total), 1, 'C');
    }

    function createLineTable($key, $producto, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 10);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5, ($producto->getProducto()->getId()), 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5,  $producto->getProducto()->getDescripcion(), 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5, "$" . $producto->getPrecio(), 1, 'C');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5, $producto->getCantidad(), 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5, "$" . $producto->getPrecio()*$producto->getCantidad(), 1, 'C');

    }

    function createLineDeuda($key, $deuda, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5, utf8_decode($key), 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5, utf8_decode("Deuda"), 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5, "$" . utf8_decode($deuda), 1, 'C');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5, utf8_decode(""), 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5, utf8_decode(""), 1, 'C');

    }

    function createBlackLineTable($pdf,$posY)
    {
        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5, '', 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'L');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'C');
    }

    function createRetirementTable($posY,$pdf)
    {
        $posY += 5;
        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(150, 20, "", 0, 'C');
        $pdf->SetXY(150, $posY);
        $pdf->MultiCell(50, 20, '', 1, 'C');
        $pdf->SetXY(150, $posY + 8);
        $pdf->MultiCell(50, 20, 'Firma:', 0, 'C');


    }

    function newPage($pdf)
    {

        $pdf->AddPage();

        //$this->Image($_SERVER['DOCUMENT_ROOT'].'/includes/images/iso/9105078191_139737_T.jpg', 15, 100, 180, 90);

        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 56);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
        $pdf->SetXY(30, 63);
        $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
        $pdf->SetXY(140, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
        $pdf->SetXY(160, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
        $pdf->SetXY(180, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');

    }

}
