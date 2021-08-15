<?php

class Conjunto
{
    private $elementos;

    public function __construct($c)
    {
        $this->elementos = $c;
    }

    public function union(Conjunto $b): Conjunto
    {
        $resultado = [];

        foreach ($this->elementos as $x) {
            if (!in_array($x, $resultado))
                $resultado[] = $x;
        }

        foreach ($b->elementos as $x) {
            if (!in_array($x, $resultado))
                $resultado[] = $x;
        }

        return new Conjunto($resultado);
    }

    public function interseccion(Conjunto $b)
    {
        $resultado = [];

        foreach ($this->elementos as $x) {
            if (in_array($x, $b->elementos) && !in_array($x, $resultado))
                $resultado[] = $x;
        }

        return new Conjunto($resultado);
    }

    public function diferencia(Conjunto $b)
    {
        $resultado = [];

        foreach ($this->elementos as $x) {
            if (!in_array($x, $b->elementos) && !in_array($x, $resultado))
                $resultado[] = $x;
        }

        return new Conjunto($resultado);
    }

    public function __toString()
    {
        return "{ " . implode(", ", $this->elementos) . " }";
    }
}
