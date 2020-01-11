<?php

namespace SaatyMethod;

/**
 * StructuringInterface is an interface that defines the first step in applying
 * the hierarchy analysis method - structuring the selection problem in 
 * the form of a hierarchy or network. In the most elementary form, 
 * the hierarchy is built from the top (goal), through intermediate 
 * levels - criteria to the lowest level, which in the general case
 * is a set of alternatives.
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface StructuringInterface
{
    /**
     * Setting criteria values
     * @param string|array $criterion
     * @return void
     */
    public function setCriterion($criterion) : void;
    
    /**
     * Setting alternatives
     * @param string|array $alternative
     * @return void
     */
    public function setAlternative($alternative) : void;
    
    /**
     * Initial initialization of data as a hierarchy
     * @param string $criterion
     * @param array $alternative
     * @return void
     */
    public function setInitialData(string $criterion, array $alternative) : void;
    
    /**
     * Returns all criteria that were initialized in the hierarchy
     * @return array
     */
    public function getCriterion() : array;
    
    /**
     * Returns all alternatives that have been initialized in the hierarchy
     * @return array
     */
    public function getAlternative() : array;
    
    /**
     * Returns network data, which is a structured representation
     * of the selection problem as a hierarchy
     * @return array
     */
    public function getInitialData() : array;
}
