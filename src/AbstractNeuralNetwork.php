<?php
namespace Zhukmax\NeuralNetwork;

/**
 * Class AbstractNeuralNetwork
 * @package Zhukmax\NeuralNetwork
 */
abstract class AbstractNeuralNetwork implements NeuralNetworkInterface
{
    const LAYER = "L";
    const NODE = "N";

    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $file;
    /**
     * @var int
     */
    public static $epoch = 1;
    /**
     * @var int
     */
    public static $iteration = 1;
    /**
     * @var array
     */
    public $network;
    /**
     * The moment
     * @var float
     */
    protected $a;
    /**
     * The speed
     * @var float
     */
    protected $e;

    /**
     * AbstractNeuralNetwork constructor.
     * @param string $title
     * @param array|null $nodes
     * @param float|null $a
     * @param float|null $e
     */
    public function __construct(string $title, $nodes = null, $a = null, float $e = null)
    {
        $this->title = $title;
        // The moment
        $this->a = $a;
        // The speed
        $this->e = $e;
        $this->path = dirname(__DIR__) . '/data/';
        $this->file = $this->path . md5($this->title) . '.ann';
        $this->read();

        // For new network
        if (!$this->network && isset($nodes)) {
            $this->makeNetwork($nodes);
        }
    }

    /**
     * @param array $nodes
     */
    protected function makeNetwork(array $nodes)
    {
        $layers = count($nodes);
        for ($layer = 0; $layer < $layers; $layer++) {
            for ($node = 0; $node < $nodes[$layer]; $node++) {
                $synapsesCount = isset($nodes[$layer - 1])? $nodes[$layer - 1]: 0;
                for ($i = 0; $i < $synapsesCount; $i++) {
                    $this->network[self::LAYER . $layer][self::NODE . $node][$i] = mt_rand(-100, 100) / 10;
                }
            }
        }

        // Save new network
        $this->write();
    }

    /**
     * @param array $input
     * @return array
     */
    abstract public function set(array $input) : array;

    /**
     * @param array $input
     * @param array $ideal
     * @return bool
     */
    abstract public function training(array $input, array $ideal) : bool;

    /**
     * @param array $purpose
     * @param array $results
     * @return float|int
     */
    abstract public function error(array $purpose, array $results);

    /**
     * @param array $purpose
     * @param array $results
     * @return string
     */
    public function errorPercent(array $purpose, array $results) : string
    {
        return round($this->error($purpose, $results) * 100, 2) . '%';
    }

    /**
     * Read data from file
     */
    protected function read()
    {
        if (file_exists($this->file)) {
            $data = json_decode(file_get_contents($this->file));

            static::$epoch     = (int)$data->epoch;
            static::$iteration = (int)$data->iteration;
            $this->network     = $data->network;
            $this->a = (float)$data->a;
            $this->e = (float)$data->e;
        }
    }

    /**
     * Wright data to file
     */
    protected function write()
    {
        if (!is_dir($this->path)) {
            mkdir($this->path);
        }

        file_put_contents($this->file, json_encode([
            'network' => $this->network,
            'epoch' => static::$epoch,
            'iteration' => static::$iteration,
            'a' => $this->a,
            'e' => $this->e
        ]));
    }
}
