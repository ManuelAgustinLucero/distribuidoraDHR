<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClienteRepository")
 */
class Cliente
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="zona", type="string", length=255)
     */
    private $zona;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="text", nullable=true)
     */
    private $detalle;
    /**
     * @var float
     *
     * @ORM\Column(name="saldo", type="float")
     */
    private $saldo;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Cliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Cliente
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set zona
     *
     * @param string $zona
     *
     * @return Cliente
     */
    public function setZona($zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return string
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Cliente
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return Cliente
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @ORM\OneToMany(targetEntity="clienteProducto", mappedBy="cliente")
     */
    private $clienteproducto;
    /**
     * @ORM\OneToMany(targetEntity="Venta", mappedBy="cliente")
     */
    private $venta;
    public function __construct()
    {
        $this->clienteproducto = new ArrayCollection();
        $this->venta = new ArrayCollection();

    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return Cliente
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Add clienteproducto
     *
     * @param \AppBundle\Entity\clienteProducto $clienteproducto
     *
     * @return Cliente
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
     * Add ventum
     *
     * @param \AppBundle\Entity\Venta $ventum
     *
     * @return Cliente
     */
    public function addVentum(\AppBundle\Entity\Venta $ventum)
    {
        $this->venta[] = $ventum;

        return $this;
    }

    /**
     * Remove ventum
     *
     * @param \AppBundle\Entity\Venta $ventum
     */
    public function removeVentum(\AppBundle\Entity\Venta $ventum)
    {
        $this->venta->removeElement($ventum);
    }

    /**
     * Get venta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVenta()
    {
        return $this->venta;
    }
    public function __toString(){
        return (string) $this->nombre;
    }
}
