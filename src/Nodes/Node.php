<?php
namespace Zhukmax\NeuralNetwork\Nodes;

use Zhukmax\NeuralNetwork\Activation;
use Zhukmax\NeuralNetwork\Exception;

/**
 * Class Node
 * @package Zhukmax\NeuralNetwork\Nodes
 */
class Node extends AbstractNode implements NodeInterface
{
    /**
     * @param array $input
     * @return float
     * @throws Exception
     */
    public function activation(array $input) : float
    {
        $count = count($input);
//        for ($i = 0; $i < $count; $i++) {
//            $this->output += $input[NeuralNetwork::NODE . $i] * $this->synapses[$i];
//        }
        foreach ($input as $item => $value) {
            $this->output += $value * $this->synapses[preg_replace('/[^0-9]/i', '', $item)];
        }

        return Activation::sigmoid($this->output);
    }
}
