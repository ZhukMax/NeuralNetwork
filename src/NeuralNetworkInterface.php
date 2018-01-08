<?php
namespace Zhukmax\NeuralNetwork;

/**
 * Interface NeuralNetworkInterface
 * @package Zhukmax\NeuralNetwork
 */
interface NeuralNetworkInterface
{
    /**
     * @param array $input
     * @return array
     */
    public function set(array $input) : array;

    /**
     * @param array $input
     * @param array $ideal
     * @return bool
     */
    public function training(array $input, array $ideal) : bool;

    /**
     * @param array $purpose
     * @param array $results
     * @return float|int
     */
    public function error(array $purpose, array $results);
}
