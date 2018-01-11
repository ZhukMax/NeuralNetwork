<?php
namespace Zhukmax\NeuralNetwork\Nodes;

/**
 * Class Bias
 * @package Zhukmax\NeuralNetwork\Nodes
 */
class Bias implements NodeInterface
{
    /**
     * @param array $input
     * @return float
     */
    public function activation(array $input) : float
    {
        return 1;
    }
}
