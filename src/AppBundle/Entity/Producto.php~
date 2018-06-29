<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductoRepository")
 */
class Producto
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Producto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }
    /**
     * @ORM\OneToMany(targetEntity="clienteProducto", mappedBy="producto")
     */
    private $clienteproducto;
    /**
     * @ORM\OneToMany(targetEntity="productoVenta", mappedBy="producto")
     */
    private $productoVenta;

    public function __construct()
    {
        $this->clienteproducto = new ArrayCollection();
        $this->productoVenta = new ArrayCollection();

    }

    /**
     * Add clienteproducto
     *
     * @param \AppBundle\Entity\clienteProducto $clienteproducto
     *
     * @return Producto
     */
    public function addClienteproducto(\AppBundle\Entity\clienteProducto $clienteproducto)
    {
        $this->clienteproducto[] = $clienteproducto;

        return $this;
    }

    /**
     * Remove clienteproducto
     *
     * @param \AppBundle\Entity\clienteProducto $clienteproducto
     */
    public function removeClienteproducto(\AppBundle\Entity\clienteProducto $clienteproducto)
    {
        $this->clienteproducto->removeElement($clienteproducto);
    }

    /**
     * Get clienteproducto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClienteproducto()
    {
        return $this->clienteproducto;
    }

    /**
     * Add productoVentum
     *
     * @param \AppBundle\Entity\productoVenta $productoVentum
     *
     * @return Producto
     */
    public function addProductoVentum(\AppBundle\Entity\productoVenta $productoVentum)
    {
        $this->productoVenta[] = $productoVentum;

        return $this;
    }

    /**
     * Remove productoVentum
     *
     * @param \AppBundle\Entity\productoVenta $productoVentum
     */
    public function removeProductoVentum(\AppBundle\Entity\productoVenta $productoVentum)
    {
        $this->productoVenta->removeElement($productoVentum);
    }

    /**
     * Get productoVenta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductoVenta()
    {
        return $this->productoVenta;
    }
}
