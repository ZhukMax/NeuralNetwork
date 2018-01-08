<?php
namespace Zhukmax\NeuralNetwork\Nodes;

/**
 * Class AbstractNode
 * @package Zhukmax\NeuralNetwork\Nodes
 */
abstract class AbstractNode
{
    /**
     * @var float
     */
    public $output = 0.0;
    /**
     * @var array
     */
    public $synapses = [];
}
