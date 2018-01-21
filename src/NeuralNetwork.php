<?php
namespace Zhukmax\NeuralNetwork;

use Zhukmax\NeuralNetwork\Nodes\Node;

/**
 * Class NeuralNetwork
 * @package Zhukmax\NeuralNetwork
 * @todo Добавить возможность создавать более одного исходящего нейрона
 */
class NeuralNetwork extends AbstractNeuralNetwork
{
    /**
     * @param array $input
     * @return array
     * @throws Exception
     */
    public function getResult(array $input) : array
    {
        $output = array();

        if (!isset($this->network)) {
            throw new Exception('Synapses of network is empty');
        }

        foreach ($input as $item => $value) {
            $output[self::LAYER . 0][self::NODE . $item] = $value;
        }

        foreach ($this->network as $layer => $nodes) {
            foreach ($nodes as $n => $synapses) {
                $node = new Node();
                $node->synapses = $synapses;
                $beforeLayer = self::LAYER . (preg_replace('/[^0-9]/i', '', $layer) - 1);
                $output[$layer][$n] = $node->activation($output[$beforeLayer]);
            }
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
        $weightChange = array();
        $layers = count((array)$this->network);
        $result = $this->getResult($input);
        $error = $this->error($ideal, [
            $result[self::LAYER . $layers][self::NODE . 0]
        ]);

        // Delta for output node
        $delta[self::LAYER . $layers][self::NODE . 0] = Activation::deltaOutput(
            $result[self::LAYER . $layers][self::NODE . 0]
        );

        for ($i = $layers - 1; $i > 0; $i--) {
            $layer = self::LAYER . $i;
            $nextLayerId = self::LAYER . ($i + 1);
            $nextLayer = $this->network->$nextLayerId;

            foreach ($this->network->$layer as $key => $node) {
                $tmpDelta = array();
                $synapses = array();

                $synapseNum = preg_replace('/[^0-9]/i', '', $key);
                foreach ($nextLayer as $nextKey => $nextNode) {
                    $tmpDelta[] = $delta[$nextLayerId][$nextKey];
                    $synapses[] = $nextNode[$synapseNum];
                }

                // Delta for nodes
                $delta[$layer][$key] = Activation::delta(
                    $result[$layer][$key],
                    $synapses,
                    $tmpDelta
                );
            }
        }

        for ($i = $layers; $i > 0; $i--) {
            $layer = self::LAYER . $i;
            $prevLayerId = self::LAYER . ($i - 1);
            $changes = count($weightChange);

            foreach ($this->network->$layer as $key => $node) {
                // Gradient for synapse
                $grad = $delta[$layer][$key] * $result[$prevLayerId][$key];

                // Weight change for synapses
                foreach ($node as $synapseId) {
                    $weightChange[$changes + 1][$layer][$key][$synapseId] = $this->e * $grad +
                        $this->a * $weightChange[$changes][$layer][$key][$synapseId];
                }
            }
        }
        print_r($weightChange);

//        // Add one iteration step
//        static::$iteration++;

//        return true;
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
