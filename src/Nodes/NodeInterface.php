<?php
namespace Zhukmax\NeuralNetwork\Nodes;

/**
 * Interface NodeInterface
 * @package Zhukmax\NeuralNetwork\Nodes
 */
interface NodeInterface
{
    /**
     * @param array $input
     * @return float
     */
    public function activation(array $input) : float;
}
