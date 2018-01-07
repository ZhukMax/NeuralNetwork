<?php
namespace Zhukmax\NeuralNetwork;

/**
 * Class Activation
 * @package Zhukmax\NeuralNetwork
 */
class Activation
{
    /**
     * @param float $x
     * @return float|int
     */
    public static function sigmoid(float $x)
    {
        return 1 / (1 + M_E ** (-1 * $x));
    }

    /**
     * @param float $x
     * @return float|int
     */
    public static function tangent(float $x)
    {
        return (M_E ** (2 * $x) - 1) / (M_E ** (2 * $x) + 1);
    }

    /**
     * @param float $x Output of node
     * @param float $w Synapse
     * @param float $d Delta of next node
     * @return float
     */
    public static function delta(float $x, float $w, float $d) : float
    {
        return ((1 - $x) * $x) * ($w * $d);
    }

    /**
     * @param float $x
     * @return float
     */
    public static function deltaOutput(float $x) : float
    {
        return (1 - $x) * ((1 - $x) * $x);
    }
}
