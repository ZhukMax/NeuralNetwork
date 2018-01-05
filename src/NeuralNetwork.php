<?php
namespace Zhukmax\NeuralNetwork;

use Zhukmax\NeuralNetwork\Nodes\Node;

/**
 * Class NeuralNetwork
 * @package Zhukmax\NeuralNetwork
 */
class NeuralNetwork extends AbstractNeuralNetwork
{
    /**
     * @param array $input
     * @return array
     * @throws Exception
     */
    public function set(array $input) : array
    {
        $output[0] = $input;

        if (isset($this->network)) {
            foreach ($this->network as $layer => $nodes) {
                foreach ($nodes as $n => $synapses) {
                    $node = new Node();
                    $node->synapses = $synapses;
                    $output[$layer][$n] = $node->activation($output[$layer - 1]);
                }
            }
        } else {
            throw new Exception('Synapses of network is empty');
        }

        return $output;
    }

    /**
     * @param array $input
     * @param array $ideal
     * @return bool
     */
    public function training(array $input, array $ideal) : bool
    {
        $layers = count($this->network);
        $result = $this->set($input);
        $error = $this->error($ideal, [$result[$layers - 1][0]]);
        $delta[$layers - 1][0] = Activation::deltaOutput($result[$layers - 1][0]);

        for ($i = $layers; $i >= 0; $i--) {
            $nodesInLayer = count($this->network);
            $delta[$i - 2][0] = Activation::delta(
                $result[$i - 2][0], $this->network[$i - 1][0], $delta[$i - 1][0]
            );

            $grad[$i - 1][0] = $delta[$i - 1][0] * $result[$i - 2][0];
        }

        // Add one iteration step
        static::$iteration++;

        return true;
    }

    /**
     * @param array $purpose
     * @param array $results
     * @return float|int
     * @throws Exception
     */
    public function error(array $purpose, array $results)
    {
        $error_amount = 0;
        for ($i = 0; $i < static::$iteration; $i++) {
            $error_amount += ($purpose[$i] - $results[$i]) ** 2;
        }

        $mse = $error_amount / static::$iteration;
        return $mse;
    }
}
