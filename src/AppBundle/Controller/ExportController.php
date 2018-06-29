<?php

namespace AppBundle\Controller;

use AppBundle\Entity\clienteProducto;
use AppBundle\Entity\Export;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Export controller.
 *
 * @Route("admin/export")
 */
class ExportController extends Controller
{
    /**
     * Lists all export entities.
     *
     * @Route("/", name="admin_export_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $exports = $em->getRepository('AppBundle:Export')->findAll();

        return $this->render('export/index.html.twig', array(
            'exports' => $exports,
        ));
    }

    /**
     * Creates a new export entity.
     *
     * @Route("/new", name="admin_export_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        set_time_limit(0);
        $export = new Export();
        $form = $this->createForm('AppBundle\Form\ExportType', $export);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($export);
            $em->flush();
            $file=$form['file']->getData();
            // Sacamos la extensión del fichero
            $ext=$file->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name='file'.".".$ext;
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $file->move("uploads", $file_name);

            //Establecemos el nombre de fichero en el atributo de la entidad
            $export->setFile($file_name);
//            $em->persist($pedido);
//            $em->flush();
            $path = "uploads/".$file_name;
            if (!file_exists($path))
                exit("File not found");
            $file = fopen($path, "r");

            if ($file) {


                while (($line = fgets($file)) !== false) {
                    $result = explode(",", $line);


                        $producto= $em->getRepository('AppBundle:Producto')->find($result[1]);
                    $cliente= $em->getRepository('AppBundle:Cliente')->find($result[2]);

                            $clienteProducto = new clienteProducto();
                            $clienteProducto->setProducto($producto);
                            $clienteProducto->setCliente($cliente);
                            $clienteProducto->setPrecio($result[3]);
                            $em->persist($clienteProducto);
                            $em->flush();









                }
                if (!feof($file)) {
                    echo "Error: EOF not found\n";
                }
                fclose($file);
   die();
            }


            return $this->redirectToRoute('admin_export_show', array('id' => $export->getId()));
        }

        return $this->render('export/new.html.twig', array(
            'export' => $export,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a export entity.
     *
     * @Route("/{id}", name="admin_export_show")
     * @Method("GET")
     */
    public function showAction(Export $export)
    {
        $deleteForm = $this->createDeleteForm($export);

        return $this->render('export/show.html.twig', array(
            'export' => $export,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing export entity.
     *
     * @Route("/{id}/edit", name="admin_export_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Export $export)
    {
        $deleteForm = $this->createDeleteForm($export);
        $editForm = $this->createForm('AppBundle\Form\ExportType', $export);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_export_edit', array('id' => $export->getId()));
        }

        return $this->render('export/edit.html.twig', array(
            'export' => $export,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a export entity.
     *
     * @Route("/{id}", name="admin_export_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Export $export)
    {
        $form = $this->createDeleteForm($export);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($export);
            $em->flush();
        }

        return $this->redirectToRoute('admin_export_index');
    }

    /**
     * Creates a form to delete a export entity.
     *
     * @param Export $export The export entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Export $export)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_export_delete', array('id' => $export->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
