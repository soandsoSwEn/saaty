<?php

namespace SaatyMethod;

/**
 * HierarchyInterface is an interface that defines methods for evaluating
 * each alternative by criteria and obtaining a final vector of priorities,
 * based on which the best of alternatives is determined
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface HierarchyInterface
{
    /**
     * Data structure initialization
     * @param \SaatyMethod\Structuring $data
     * @return void
     */
    public function setStructureData(Structuring $data) : void;
    
    /**
     * Returns data structures
     * @return object
     */
    public function getStructureData() : object;
    
    /**
     * Sets the resulting data set to solve the selection problem as a hierarchy
     * @param array $pairwise_comparisons
     * @return void
     */
    public function setSummaryData(array $pairwise_comparisons) : void;
    
    /**
     * Returns the resulting dataset
     * @return array
     */
    public function getSummaryData() : array;
    
    /**
     * Defines the best alternative in a given hierarchy selection task
     * @param array $data
     * @return string
     */
    public function getBestAlternative(array $data) : string;
}
